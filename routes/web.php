<?php

use App\Livewire\Site\LoginController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

/* Rutas del sitio */
Route::get('/', App\Livewire\Site\HomeController::class)->name('home-site');
Route::get('login', App\Livewire\Site\LoginController::class)->name('login');
Route::get('register', App\Livewire\Site\RegisterController::class)->name('register');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('home', App\Livewire\Site\HomeSiteController::class)->name('home');
    Route::get('escena', App\Livewire\Site\EscenaController::class)->name('escena');
    Route::get('dashboard', App\Livewire\Site\DashboardController::class)->name('dashboard');
    Route::get('reservaciones', App\Livewire\Site\ReservacionesController::class)->name('reservaciones');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin-dashboard', App\Livewire\Site\AdminPanelController::class)->name('admin-dashboard');
    Route::get('admin-schedule', App\Livewire\Site\AdminScheduleController::class)->name('admin-schedule');
    Route::get('admin-calendary', App\Livewire\Site\AdminCalendaryController::class)->name('admin-calendary');
    Route::get('admin-user', App\Livewire\Site\AdminUserController::class)->name('admin-user');
    Route::get('admin-scene', App\Livewire\Site\AdminSceneController::class)->name('admin-scene');
    Route::get('admin-scene-category', App\Livewire\Site\AdminSceneCategoryController::class)->name('admin-scene-category');
    Route::get('admin-mantenaince', App\Livewire\Site\AdminMantenainceController::class)->name('admin-mantenaince');
    Route::get('admin-mantenaince-vr', App\Livewire\Site\AdminVrMantenainceController::class)->name('admin-mantenaince-vr');
    Route::get('admin-room', App\Livewire\Site\AdminRoomController::class)->name('admin-room');
    Route::get('admin-vr-glasses', App\Livewire\Site\AdminVrController::class)->name('admin-vr-glasses');
    //Route::get('admin-auth', App\Livewire\Site\AdminAuthController::class)->name('admin-auth');
});


/* Rutas del admin */