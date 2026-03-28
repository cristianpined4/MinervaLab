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

        if (!$this->reservation_id) {
            $this->error = 'No se recibió una sesión válida para registrar asistencia.';
            $this->canRegister = false;
            return;
        }

        if (!$this->reservation) {
            $this->error = 'No existe la reservación especificada.';
            $this->canRegister = false;
            return;
        }

        $this->refreshSessionState();
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

    private function refreshSessionState(): void
    {
        $this->canRegister = false;

        if (!$this->reservation) {
            $this->error = 'No existe la reservación especificada.';
            return;
        }

        $reservationDate = Carbon::parse($this->reservation->date)->format('Y-m-d');
        $today = now()->format('Y-m-d');
        $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $reservationDate . ' ' . $this->reservation->starts_at);
        $endsAt = Carbon::createFromFormat('Y-m-d H:i:s', $reservationDate . ' ' . $this->reservation->ends_at);
        $now = now();

        if ((int) $this->reservation->status !== 1) {
            $this->error = 'Esta reservación no está disponible para registro de asistencia.';
            return;
        }

        if ($reservationDate !== $today) {
            if ($reservationDate > $today) {
                $this->error = 'La sesión aún no ha comenzado. Intenta durante el horario de la reservación.';
            } else {
                $this->error = 'La sesión ya finalizó. El registro de asistencia está cerrado.';
            }
            return;
        }

        if ($now->lt($startsAt)) {
            $this->error = 'La sesión aún no ha comenzado. Intenta durante el horario de la reservación.';
            return;
        }

        if ($now->gte($endsAt)) {
            $this->error = 'La sesión ya finalizó. El registro de asistencia está cerrado.';
            return;
        }

        $this->error = null;
        $this->canRegister = true;
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

        $this->refreshSessionState();

        if (!$this->canRegister) {
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
            $this->refreshSessionState();
            return;
        }

        $normalizedCarnet = mb_strtoupper(trim((string) $this->carnet));

        if ($normalizedCarnet === '') {
            $this->error = 'El carnet es obligatorio para registrar asistencia.';
            return;
        }

        $alreadyRegistered = ReservationAttendance::query()
            ->where('id_reservation', $this->reservation->id)
            ->whereRaw('UPPER(TRIM(carnet)) = ?', [$normalizedCarnet])
            ->exists();

        if ($alreadyRegistered) {
            $this->error = 'Este carnet ya registró asistencia en esta sesión.';
            return;
        }

        // Registrar asistencia
        ReservationAttendance::create([
            'id_reservation' => $this->reservation->id,
            'carnet' => $normalizedCarnet,
            'date' => now(),
            'attendance' => now()->format('H:i:s'),
        ]);

        $this->dispatch('swal:notify', ['message' => 'Asistencia registrada', 'type' => 'success', 'url' => url('/') . '/attendance-success']);
    }
}
