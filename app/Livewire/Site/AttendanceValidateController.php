<?php
namespace App\Livewire\Site;

use App\Models\Reservation;
use App\Models\ReservationAttendance;
use Livewire\Component;

class AttendanceValidateController extends Component
{
    public $reservation_id;
    public $reservation;

    public $carnet;
    public $error = null;

    public function mount()
    {
        // Tomar ?session=ID
        $this->reservation_id = request()->get('session');

        // Cargar reservación
        if ($this->reservation_id) {
            $this->reservation = Reservation::find($this->reservation_id);
        }
    }

    public function render()
    {
        return view('livewire.admin.auth.access_attendance')
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }

    public function register()
    {
        $this->error = null;

        $this->validate([
            'carnet' => 'required'
        ], [
            'carnet.required' => 'El carnet es obligatorio para registrar asistencia.'
        ]);

        if (!$this->reservation) {
            $this->error = "No existe la reservación especificada.";
            return;
        }

        // Registrar asistencia
        ReservationAttendance::create([
            'id_reservation' => $this->reservation->id,
            'carnet'         => $this->carnet,
            'date'           => now(),
            'attendance'     => now()->format('H:i:s'),
        ]);

        $this->dispatch('swal:notify', ['message' => 'Asistencia registrada', 'type' => 'success', 'url' => url('/') . '/attendance-success']);
    }
}
