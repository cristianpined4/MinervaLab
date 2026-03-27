<?php
namespace App\Livewire\Site;

use App\Models\Reservation;
use App\Models\ReservationAttendance;
use Livewire\Component;
use Carbon\Carbon;

class AttendanceValidateController extends Component
{
    public $reservation_id;
    public $reservation;
    public $canRegister = false;

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

        $this->canRegister = $this->isReservationActive();

        if ($this->reservation && !$this->canRegister) {
            $this->error = 'Esta reservación no está en curso. No se permite registrar asistencia.';
            $this->reservation = null;
        }
    }

    private function isReservationActive(): bool
    {
        if (!$this->reservation) {
            return false;
        }

        $now = now();
        $reservationDate = Carbon::parse($this->reservation->date)->format('Y-m-d');
        $today = $now->format('Y-m-d');
        $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $reservationDate . ' ' . $this->reservation->starts_at);
        $endsAt = Carbon::createFromFormat('Y-m-d H:i:s', $reservationDate . ' ' . $this->reservation->ends_at);

        return
            (int) $this->reservation->status === 1
            && $reservationDate === $today
            && $now->gte($startsAt)
            && $now->lt($endsAt);
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

        if (!$this->canRegister) {
            $this->error = 'Esta reservación no está en curso. No se permite registrar asistencia.';
            return;
        }

        $this->validate([
            'carnet' => 'required'
        ], [
            'carnet.required' => 'El carnet es obligatorio para registrar asistencia.'
        ]);

        if (!$this->reservation) {
            $this->error = "No existe la reservación especificada.";
            return;
        }

        if (!$this->isReservationActive()) {
            $this->error = 'Esta reservación no está en curso. No se permite registrar asistencia.';
            return;
        }

        // Registrar asistencia
        ReservationAttendance::create([
            'id_reservation' => $this->reservation->id,
            'carnet' => $this->carnet,
            'date' => now(),
            'attendance' => now()->format('H:i:s'),
        ]);

        $this->dispatch('swal:notify', ['message' => 'Asistencia registrada', 'type' => 'success', 'url' => url('/') . '/attendance-success']);
    }
}
