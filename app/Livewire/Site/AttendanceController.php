<?php

namespace App\Livewire\Site;

use App\Models\Room;
use App\Models\Reservation;
use Livewire\Component;
use Exception;

class AttendanceController extends Component
{
    public $rooms;
    public $selectedRoomId;
    public $reservations = [];
    public $selectedReservationId;
    public $showQR = false;
    public $qrImage = '';
    public $currentTime;
    public $notificationShown = false;
    public $autoSelectOnce = true;
    public $playActivationSound = false;
    public $playEndingSound = false;
    public $lastAutoSelectedId = null;

    protected $listeners = ['refreshReservations' => 'loadReservations'];

    public function mount()
    {
        $this->rooms = Room::select('id', 'description', 'status')->get();
        $this->currentTime = now();
        // Limpiar QR códigos viejos al montar el componente
        $this->cleanOldQRCodes();
    }

    public function render()
    {
        return view('livewire.admin.auth.sel_room')
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }

    /**
     * Seleccionar una sala y cargar sus reservaciones del día
     */
    public function selectRoom($roomId)
    {
        $this->selectedRoomId = $roomId;
        $this->selectedReservationId = null;
        $this->showQR = false;
        $this->notificationShown = false;
        $this->autoSelectOnce = true; // Permitir auto-selección en esta nueva sala

        $this->loadReservations();
    }

    /**
     * Cargar reservaciones del día para la sala seleccionada
     */
    public function loadReservations()
    {
        if (!$this->selectedRoomId) {
            $this->reservations = [];
            return;
        }

        $this->currentTime = now();

        // Obtener reservaciones ordenadas por hora de inicio (con usuario precargado)
        $reservations = Reservation::where('id_room', $this->selectedRoomId)
            ->whereDate('date', today())
            ->where('status', 1)
            ->with('HasUser')
            ->orderBy('starts_at', 'asc')
            ->get();

        $currentTimeStr = now()->format('H:i:s');

        $this->reservations = $reservations->map(function ($res) use ($currentTimeStr) {
            // Acceder a la relación precargada
            $user = $res->HasUser;
            $userName = $user ? $user->name : 'Sin usuario';
            $userUsername = $user ? $user->username : '-';

            // Determinar estado
            $isActive = $res->starts_at <= $currentTimeStr && $res->ends_at >= $currentTimeStr;
            $isPassed = $res->ends_at < $currentTimeStr;

            return [
                'id' => $res->id,
                'starts_at' => $res->starts_at,
                'ends_at' => $res->ends_at,
                'user' => $userName,
                'username' => $userUsername,
                'students' => $res->students ?? 0,
                'status' => 'pending',
                'isActive' => $isActive,
                'isPassed' => $isPassed,
            ];
        })->toArray();

        // Ordenar: próximas, activas, pasadas
        $this->reservations = $this->sortReservations($this->reservations);

        // Solo auto-seleccionar en la primera carga de esta sala
        if ($this->autoSelectOnce && $this->selectedReservationId === null) {
            $this->autoSelectCurrentReservation();
            $this->autoSelectOnce = false;
        }
    }

    /**
     * Ordenar reservaciones: próximas > activas > pasadas
     */
    private function sortReservations($reservations)
    {
        $currentTimeStr = now()->format('H:i:s');

        usort($reservations, function ($a, $b) use ($currentTimeStr) {
            // Si ambas son activas, mantener orden original
            if ($a['isActive'] && $b['isActive']) {
                return 0;
            }
            // Activas primero
            if ($a['isActive'])
                return -1;
            if ($b['isActive'])
                return 1;

            // Si ambas son próximas, ordenar por hora más cercana
            if (!$a['isPassed'] && !$b['isPassed']) {
                return strcmp($a['starts_at'], $b['starts_at']);
            }
            // Próximas antes que pasadas
            if (!$a['isPassed'])
                return -1;
            if (!$b['isPassed'])
                return 1;

            // Pasadas: más recientes primero
            return strcmp($b['ends_at'], $a['ends_at']);
        });

        return $reservations;
    }

    /**
     * Auto-seleccionar la reservación que está actualmente en progreso
     */
    private function autoSelectCurrentReservation()
    {
        $currentTimeStr = now()->format('H:i:s');

        // Buscar la reservación activa actualmente
        foreach ($this->reservations as $res) {
            if ($res['isActive']) {
                // Si es diferente de la última autoseleccionada, reproducir sonido
                if ($res['id'] != $this->lastAutoSelectedId) {
                    $this->lastAutoSelectedId = $res['id'];
                    // Emitir evento para reproducir sonido
                    $this->dispatch('playActivationSound');
                }

                $this->selectedReservationId = $res['id'];
                $this->generateQR();
                return;
            }
        }

        // Si no hay reservación actual, no mostrar QR
        $this->showQR = false;
        $this->selectedReservationId = null;
    }

    /**
     * Seleccionar una reservación y generar QR
     */
    public function selectReservation($reservationId)
    {
        if ($this->selectedReservationId === $reservationId && !empty($this->qrImage)) {
            return; // Ya está seleccionada y QR generado
        }

        $this->selectedReservationId = $reservationId;
        $this->notificationShown = false;
        $this->qrImage = ''; // Limpiar QR anterior
        $this->showQR = false; // Ocultar QR mientras se genera

        // Generar QR de inmediato
        $this->generateQR();

        // Verificar si está próxima a finalizar
        $this->checkEndingTime();
    }

    /**
     * Verificar si la reservación está próxima a finalizar (5 minutos)
     */
    private function checkEndingTime()
    {
        $reservation = null;
        foreach ($this->reservations as $res) {
            if ($res['id'] == $this->selectedReservationId) {
                $reservation = $res;
                break;
            }
        }

        if ($reservation && $reservation['isActive']) {
            $endsAt = \Carbon\Carbon::createFromFormat('H:i:s', $reservation['ends_at']);
            $minutesUntilEnd = $endsAt->diffInMinutes(now());

            if ($minutesUntilEnd <= 5 && $minutesUntilEnd >= 0 && !$this->notificationShown) {
                $this->notificationShown = true;
                $this->dispatch('playEndingSound');
                $this->dispatch('swal:notify', [['icon' => 'warning', 'message' => "⏰ ¡Reservación finaliza en $minutesUntilEnd minuto" . ($minutesUntilEnd != 1 ? 's' : '') . '!']]);
            }
        }
    }

    /**
     * Generar código QR para la reservación seleccionada (en memoria con base64)
     */
    public function generateQR()
    {
        if (!$this->selectedReservationId) {
            $this->showQR = false;
            $this->qrImage = '';
            return;
        }

        try {
            $reservation = Reservation::findOrFail($this->selectedReservationId);

            // Valor QR: URL + ID de la reserva actual
            $qrData = url('/') . '/attendance?session=' . $reservation->id;

            // Generar QR en memoria con buffer output
            if (file_exists(public_path('phpqrcode/qrlib.php'))) {
                include_once public_path('phpqrcode/qrlib.php');

                // Usar buffer de salida para capturar PNG
                ob_start();
                \QRcode::png($qrData, false, QR_ECLEVEL_L, 6);
                $qrPNG = ob_get_clean();

                // Convertir a base64
                $base64 = base64_encode($qrPNG);
                $this->qrImage = 'data:image/png;base64,' . $base64;
                $this->showQR = true;
            }
        } catch (Exception $e) {
            $this->showQR = false;
            $this->qrImage = '';
        }
    }

    /**
     * Limpiar carpeta de QR códigos antiguos
     */
    public function cleanOldQRCodes()
    {
        $qrcodeDir = public_path('qrcodes/');
        if (is_dir($qrcodeDir)) {
            $files = glob($qrcodeDir . '*.png');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
}