<?php

namespace App\Livewire\Site;

use App\Models\News;
use App\Models\Reservation;
use Livewire\Component;

class HomeSiteController extends Component
{
    public function render()
    {
        // Últimas noticias (artículos, imágenes, videos) para el feed
        $news = News::orderByDesc('date')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        // Próximas reservaciones del usuario autenticado
        $upcomingReservations = Reservation::where('id_user', auth()->id())
            ->whereDate('date', '>=', today())
            ->where('status', 1) // aprobadas
            ->orderBy('date')
            ->with('HasRoom')
            ->take(3)
            ->get();

        return view('livewire.site.home-site', compact('news', 'upcomingReservations'))
            ->extends('layouts.site')
            ->section('content');
    }
}
