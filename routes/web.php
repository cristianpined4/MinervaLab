<?php

use Illuminate\Support\Facades\Route;

/* Rutas del sitio */
Route::get('/', App\Livewire\Site\HomeController::class)->name('home-site');
Route::get('login', App\Livewire\Site\LoginController::class)->name('login');
Route::get('register', App\Livewire\Site\RegisterController::class)->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('home', App\Livewire\Site\HomeSiteController::class)->name('home');
    Route::get('escena', App\Livewire\Site\EscenaController::class)->name('escena');
    Route::get('dashboard', App\Livewire\Site\DashboardController::class)->name('dashboard');
    Route::get('reservaciones', App\Livewire\Site\ReservacionesController::class)->name('reservaciones');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin-dashboard', App\Livewire\Site\AdminPanelController::class)->name('admin-dashboard');
    Route::get('admin-calendary', App\Livewire\Site\AdminCalendaryController::class)->name('admin-calendary');
});


/* Rutas del admin */