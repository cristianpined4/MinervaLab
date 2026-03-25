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
    public $lastEndingMinute = null;
    public $lastReservationEndedAt = null; // Rastrear cuándo pasó la última reservación

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
        $this->autoSelectOnce = true;
        $this->lastAutoSelectedId = null; // Resetear autoselección
        $this->lastEndingMinute = null; // Resetear contador de minutos
        $this->lastReservationEndedAt = null; // Resetear fin de reservación anterior

        $this->loadReservations();
    }

    /**
     * Cargar reservaciones del día para la sala seleccionada
     */
    public function loadReservations()
    {
        if (!$this->selectedRoomId) {
            $this->reservations = [];
            $this->selectedReservationId = null;
            $this->showQR = false;
            $this->lastEndingMinute = null;
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
            $userName = $user ? $user->first_name . ' ' . $user->last_name : 'Sin Nombre';
            $userUsername = $user ? $user->username : '-';
            $rol_user = $user ? $user->Rol->name : 'Sin Rol';

            // Determinar estado
            $isActive = $res->starts_at <= $currentTimeStr && $res->ends_at >= $currentTimeStr;
            $isPassed = $res->ends_at < $currentTimeStr;

            return [
                'id' => $res->id,
                'starts_at' => $res->starts_at,
                'ends_at' => $res->ends_at,
                'user' => $userName,
                'rol_user' => $rol_user,
                'username' => $userUsername,
                'students' => $res->students ?? 0,
                'status' => 'pending',
                'isActive' => $isActive,
                'isPassed' => $isPassed,
            ];
        })->toArray();

        // Ordenar: próximas, activas, pasadas
        $this->reservations = $this->sortReservations($this->reservations);

        // Validar que la reservación seleccionada aún pertenece a esta sala
        if ($this->selectedReservationId !== null) {
            $reservationStillExists = false;
            foreach ($this->reservations as $res) {
                if ($res['id'] == $this->selectedReservationId) {
                    $reservationStillExists = true;
                    break;
                }
            }

            // Si la reservación no existe en esta sala, deseleccionar
            if (!$reservationStillExists) {
                \Log::info("❌ Reservación {$this->selectedReservationId} no existe en la sala");
                $this->selectedReservationId = null;
                $this->showQR = false;
                $this->lastEndingMinute = null;
                $this->lastReservationEndedAt = null;
            }
        }

        // Validar que la selección actual sigue siendo válida
        $this->validateCurrentSelection();

        // Si NO hay selección manual, intentar auto-seleccionar la activa
        if ($this->selectedReservationId === null) {
            \Log::info("🔄 Intentando auto-seleccionar (selectedReservationId es null)");
            $this->autoSelectCurrentReservation();
        }

        // Verificar tiempo restante de la reservación seleccionada
        $this->checkEndingTimeMinute();
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
     * Validar que la reservación actualmente seleccionada aún sea válida y no esté pasada
     */
    private function validateCurrentSelection()
    {
        if (!$this->selectedReservationId) {
            return;
        }

        // Buscar la reservación seleccionada
        $selected = null;
        foreach ($this->reservations as $res) {
            if ($res['id'] == $this->selectedReservationId) {
                $selected = $res;
                break;
            }
        }

        // Si no existe, deseleccionar
        if (!$selected) {
            \Log::info("❌ Reservación {$this->selectedReservationId} no existe - deseleccionando");
            $this->selectedReservationId = null;
            $this->showQR = false;
            $this->lastEndingMinute = null;
            return;
        }

        // Si está pasada, deseleccionar PERO no aquí (se hace en checkEndingTimeMinute)
        if ($selected['isPassed']) {
            \Log::info("⏳ Reservación {$this->selectedReservationId} está pasada");
        }
    }

    /**
     * Auto-seleccionar la reservación que está actualmente en progreso
     * SOLO ejecutar si NO hay selección manual
     */
    private function autoSelectCurrentReservation()
    {
        // IMPORTANTE: Este método SOLO se llama si selectedReservationId === null
        // No debe sobrescribir selecciones manuales del usuario

        if (!$this->selectedRoomId) {
            \Log::info("❌ Sin sala seleccionada");
            return;
        }

        // Buscar la reservación activa actualmente
        foreach ($this->reservations as $res) {
            if ($res['isActive']) {
                // Si es diferente de la última autoseleccionada, reproducir sonido
                if ($res['id'] != $this->lastAutoSelectedId) {
                    $this->lastAutoSelectedId = $res['id'];
                    $this->lastEndingMinute = null;
                    $this->lastReservationEndedAt = null;
                    \Log::info("✅ Auto-seleccionando {$res['id']} - playActivationSound");
                    $this->dispatch('playActivationSound');
                }

                $this->selectedReservationId = $res['id'];
                $this->notificationShown = false;
                $this->generateQR();
                $this->checkEndingTimeMinute();
                return;
            }
        }

        \Log::info("ℹ️ No hay reservación activa para auto-seleccionar");
    }

    /**
     * Seleccionar una reservación manualmente y generar QR
     */
    public function selectReservation($reservationId)
    {
        \Log::info("👆 Usuario click en reservación {$reservationId}");

        // Validar que la reservación pertenece a la sala seleccionada
        $isValidReservation = false;
        foreach ($this->reservations as $res) {
            if ($res['id'] == $reservationId) {
                $isValidReservation = true;
                break;
            }
        }

        if (!$isValidReservation) {
            \Log::warning("⚠️ Reservación {$reservationId} no válida para esta sala");
            return;
        }

        // Si es la misma y QR ya está generado, no hacer nada
        if ($this->selectedReservationId === $reservationId && !empty($this->qrImage)) {
            \Log::info("ℹ️ Reservación {$reservationId} ya está seleccionada");
            return;
        }

        // Seleccionar la nueva reservación
        $this->selectedReservationId = $reservationId;
        \Log::info("✅ Seleccionada reservación {$reservationId}");

        // Resetear contadores SOLO si cambió de reservación
        $this->notificationShown = false;
        $this->lastEndingMinute = null; // Permite sonido al cambiar de res
        $this->lastReservationEndedAt = null; // Resetea el contador de finalización
        $this->qrImage = ''; // Limpiar QR anterior
        $this->showQR = false; // Ocultar QR mientras se genera

        // Generar QR de inmediato
        $this->generateQR();

        // Verificar tiempo restante
        $this->checkEndingTimeMinute();
    }

    /**
     * Verificar si la reservación está próxima a finalizar (5 minutos) - Antigua versión (no usar)
     */
    private function checkEndingTime()
    {
        // Esta función ha sido reemplazada por checkEndingTimeMinute()
        // Se mantiene solo por compatibilidad
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

    /**
     * Verificar cada minuto si la reservación está próxima a finalizar (5 minutos o menos)
     */
    private function checkEndingTimeMinute()
    {
        // Validar que hay sala y reservación seleccionada
        if (!$this->selectedRoomId || !$this->selectedReservationId) {
            return;
        }

        // Buscar la reservación SOLO en las reservaciones de la sala seleccionada
        $reservation = null;
        foreach ($this->reservations as $res) {
            if ($res['id'] == $this->selectedReservationId) {
                $reservation = $res;
                break;
            }
        }

        // Si no encontró la reservación en la sala actual, deseleccionar
        if (!$reservation) {
            $this->selectedReservationId = null;
            $this->showQR = false;
            $this->lastEndingMinute = null;
            $this->lastReservationEndedAt = null;
            return;
        }

        // Crear Carbon con FECHA DE HOY + hora de finalización
        $today = now()->format('Y-m-d');
        $endsAtTime = $reservation['ends_at'];
        $startsAtTime = $reservation['starts_at'];

        // Verificar si ya contiene la fecha (si tiene más de 8 caracteres o contiene guiones)
        if (strlen($endsAtTime) > 8 || str_contains($endsAtTime, '-')) {
            // Ya tiene fecha completa, usar directamente
            $endsAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endsAtTime);
        } else {
            // Solo tiene hora, concatenar con fecha de hoy
            $endsAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $today . ' ' . $endsAtTime);
        }

        if (strlen($startsAtTime) > 8 || str_contains($startsAtTime, '-')) {
            $startsAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $startsAtTime);
        } else {
            $startsAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $today . ' ' . $startsAtTime);
        }

        $now = now();

        $secondsUntilEnd = $now->diffInSeconds($endsAt);
        // Calcular minutos redondeados hacia ARRIBA (si hay 4:58, debe contar como 5 minutos)
        $minutesUntilEnd = $secondsUntilEnd > 0 ? (int) ceil($secondsUntilEnd / 60) : 0;

        // Recalcular isPassed e isActive en tiempo real
        $isPassed = $now >= $endsAt; // Ahora > fecha fin
        $isActive = $now >= $startsAt && $now < $endsAt; // Ahora está entre inicio y fin

        \Log::info("✅ COUNTDOWN DEBUG", [
            'endsAtTime' => $endsAtTime,
            'endsAt' => $endsAt->format('Y-m-d H:i:s'),
            'now' => $now->format('Y-m-d H:i:s'),
            'secondsUntilEnd' => $secondsUntilEnd,
            'minutesUntilEnd' => $minutesUntilEnd,
            'isActive' => $isActive,
            'isPassed' => $isPassed,
        ]);

        // Si la reservación ya pasó, handle deselección
        if ($isPassed) {
            // Solo disparar el evento una sola vez usando el ID como identificador
            $currentResId = (int) ($this->selectedReservationId ?? 0);
            $lastResId = (int) ($this->lastReservationEndedAt ?? 0);

            if ($currentResId !== 0 && $currentResId !== $lastResId) {
                // Disparar evento solo una vez
                $this->lastReservationEndedAt = $this->selectedReservationId;
                \Log::info("🔊 Despachando: playEndingSound para reservación {$currentResId}");
                $this->dispatch('playEndingSound');
                \Log::info('📢 Despachando: swal:notify - Finalizada');
                $this->dispatch('swal:notify', icon: 'success', title: '✓ Reservación Finalizada', message: '✓ Reservación Finalizada');
            }

            // Deseleccionar inmediatamente (no esperar a que sea < -1)
            \Log::info("🗑️ Deseleccionando reservación {$currentResId} (ya pasó - {$secondsUntilEnd}s)");
            $this->selectedReservationId = null;
            $this->showQR = false;
            $this->notificationShown = false;
            $this->lastEndingMinute = null;
            // NO resetear lastReservationEndedAt aquí - mantenerlo para evitar duplicados en próximo ciclo
            return;
        }

        // Si está activa y faltan 5 minutos o menos
        if ($isActive && $minutesUntilEnd <= 5 && $minutesUntilEnd >= 0) {
            // Rastrear el minuto actual (sin segundos)
            $currentMinute = $now->format('H:i');

            // Solo reproducir sonido si es un minuto diferente al anterior
            if ($this->lastEndingMinute !== $currentMinute) {
                $this->lastEndingMinute = $currentMinute;
                \Log::info("🔔 Despachando: playCountdownSound ({$minutesUntilEnd} min)");
                $this->dispatch('playCountdownSound');
                \Log::info("📣 Despachando: swal:notify - Faltan {$minutesUntilEnd} minuto(s)");
                $minutosText = "⏰ ¡Faltan {$minutesUntilEnd} minuto" . ($minutesUntilEnd != 1 ? 's' : '') . '!';
                $this->dispatch('swal:notify', icon: 'warning', title: $minutosText, message: $minutosText);
            }
        } elseif ($minutesUntilEnd > 5) {
            // Resetear si pasan más de 5 minutos
            $this->lastEndingMinute = null;
            $this->notificationShown = false;
        }
    }
}