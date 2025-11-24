<?php

namespace App\Livewire\Site;

use App\Models\Room;
use App\Models\Reservation;
use Livewire\Component;
use Exception;

class AttendanceController extends Component
{
    public $rooms;
    public $showQR = false;
    public $qrData = '';
    public $qrImage = '';

    public function mount()
    {
        $this->rooms = Room::select('id','description','status')->get();
    }

    public function render()
    {
        return view('livewire.admin.auth.sel_room')
            ->extends('layouts.loginAndRegister')
            ->section('content');

    }
    public function selectRoom($roomId)
    {
        $currentTime = now()->format('H:i:s');
        $activeReservation = Reservation::where('id_room', $roomId)
            ->whereDate('date', today()) // la hora no importa
            ->where('starts_at', '<=', $currentTime)
            ->where('ends_at', '>=', $currentTime)
            ->where('status', 1)
            ->first();

        // SI NO HAY RESERVA ACTIVA → NO MOSTRAR QR
        if (!$activeReservation) {
            $this->dispatch('swal:notify', ['message' => 'No hay una reserva activa en esta sala', 'type' => 'error']);
            return;
        }

        // SI EXISTE RESERVA ACTIVA
        $this->showQR = true;

        // Valor QR: URL + ID de la reserva activa
        $this->qrData = url('/') . '/attendance?session=' . $activeReservation->id;

        // Directorio de QR temporal
        $tempDir = public_path('qrcodes/');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $fileName = 'qr_' . $roomId . '.png';
        $filePath = $tempDir . $fileName;

        // Generar QR
        include_once public_path('phpqrcode/qrlib.php');
        \QRcode::png($this->qrData, $filePath, QR_ECLEVEL_L, 6);

        $this->qrImage = asset('qrcodes/' . $fileName);
    }




}
