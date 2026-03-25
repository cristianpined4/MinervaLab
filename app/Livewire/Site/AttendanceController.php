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

    protected $listeners = ['refreshReservations' => 'loadReservations'];

    public function mount()
    {
        $this->rooms = Room::select('id', 'description', 'status')->get();
        $this->currentTime = now();
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

        // Obtener reservaciones ordenadas por hora de inicio
        $this->reservations = Reservation::where('id_room', $this->selectedRoomId)
            ->whereDate('date', today())
            ->where('status', 1)
            ->orderBy('starts_at', 'asc')
            ->get()
            ->map(function ($res) {
                return [
                    'id' => $res->id,
                    'starts_at' => $res->starts_at,
                    'ends_at' => $res->ends_at,
                    'user' => $res->HasUser()?->first()?->name ?? 'Usuario desconocido',
                    'students' => $res->students ?? 0,
                    'status' => 'pending',
                ];
            })
            ->toArray();

        // Auto-seleccionar la reservación actual
        $this->autoSelectCurrentReservation();
    }

    /**
     * Auto-seleccionar la reservación que está actualmente en progreso
     */
    private function autoSelectCurrentReservation()
    {
        $currentTimeStr = now()->format('H:i:s');

        foreach ($this->reservations as $res) {
            if ($res['starts_at'] <= $currentTimeStr && $res['ends_at'] >= $currentTimeStr) {
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
        $this->selectedReservationId = $reservationId;
        $this->notificationShown = false;
        $this->generateQR();
    }

    /**
     * Generar código QR para la reservación seleccionada
     */
    private function generateQR()
    {
        if (!$this->selectedReservationId) {
            $this->showQR = false;
            return;
        }

        $reservation = Reservation::find($this->selectedReservationId);
        if (!$reservation) {
            return;
        }

        // Valor QR: URL + ID de la reserva actual
        $qrData = url('/') . '/attendance?session=' . $reservation->id;

        // Directorio de QR temporal
        $tempDir = public_path('qrcodes/');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $fileName = 'qr_room_' . $this->selectedRoomId . '_res_' . $reservation->id . '.png';
        $filePath = $tempDir . $fileName;

        // Generar QR
        include_once public_path('phpqrcode/qrlib.php');
        \QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 6);

        $this->qrImage = asset('qrcodes/' . $fileName);
        $this->showQR = true;
    }
}

