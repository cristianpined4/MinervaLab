<?php

use Illuminate\Support\Facades\Route;

/* Rutas del sitio */
Route::get('/', App\Livewire\Site\HomeController::class)->name('home-site');
Route::get('login', App\Livewire\Site\LoginController::class)->name('login');
Route::get('register', App\Livewire\Site\RegisterController::class)->name('register');
Route::get('dashboard', App\Livewire\Site\DashboardController::class)->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('home', App\Livewire\Site\HomeSiteController::class)->name('home');
    Route::get('reservaciones', App\Livewire\Site\ReservacionesController::class)->name('reservaciones');
    Route::get('escena', App\Livewire\Site\EscenaController::class)->name('escena');
});



/* Rutas del admin */