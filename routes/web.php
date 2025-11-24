<?php

use App\Livewire\Site\LoginController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

/* Rutas del sitio */
Route::get('/', App\Livewire\Site\HomeController::class)->name('home-site');
Route::get('login', App\Livewire\Site\LoginController::class)->name('login');
Route::get('access', App\Livewire\Site\AccessController::class)->name('access');
Route::get('set-attendance', App\Livewire\Site\AttendanceController::class)->name('set-attendance');
Route::get('attendance', App\Livewire\Site\AttendanceValidateController::class)->name('attendance');
Route::get('attendance-success', App\Livewire\Site\AttendanceSuccessController::class)->name('attendance-success');
Route::get('register', App\Livewire\Site\RegisterController::class)->name('register');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('home', App\Livewire\Site\HomeSiteController::class)->name('home');
    Route::get('reservation', App\Livewire\Site\ReservationController::class)->name('reservation');
    Route::get('dashboard', App\Livewire\Site\DashboardController::class)->name('dashboard');
    Route::get('my-reservations', App\Livewire\Site\MyReservationController::class)->name('my-reservations');

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
    Route::get('admin-reservation', App\Livewire\Site\AdminReservationController::class)->name('admin-reservation');
    Route::get( 'admin-vr-glasses', App\Livewire\Site\AdminVrController::class)->name('admin-vr-glasses');
});


/* Rutas del admin */