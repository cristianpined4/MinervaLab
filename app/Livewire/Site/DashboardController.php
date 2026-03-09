<?php

namespace App\Livewire\Site;

use App\Models\Reservation;
use App\Models\Scene;
use App\Models\Room;
use Livewire\Component;

class DashboardController extends Component
{
    public function render()
    {
        $userId = auth()->id();

        // Totales de reservaciones del usuario
        $totalReservations = Reservation::where('id_user', $userId)->count();
        $approvedReservations = Reservation::where('id_user', $userId)->where('status', 1)->count();
        $pendingReservations = Reservation::where('id_user', $userId)->where('status', 0)->count();
        $declinedReservations = Reservation::where('id_user', $userId)->where('status', 2)->count();

        // Próximas reservaciones aprobadas
        $upcoming = Reservation::where('id_user', $userId)
            ->whereDate('date', '>=', today())
            ->where('status', 1)
            ->orderBy('date')
            ->with('HasRoom')
            ->take(5)
            ->get();

        // Historial reciente (últimas 5)
        $recent = Reservation::where('id_user', $userId)
            ->orderByDesc('id')
            ->with('HasRoom')
            ->take(5)
            ->get();

        return view('livewire.site.dashboard', compact(
            'totalReservations',
            'approvedReservations',
            'pendingReservations',
            'declinedReservations',
            'upcoming',
            'recent'
        ))
            ->extends('layouts.site')
            ->section('content');
    }
}
