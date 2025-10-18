<?php

use Illuminate\Support\Facades\Route;

/* Rutas del sitio */
Route::get('/', App\Livewire\Site\HomeController::class)->name('home-site');
Route::get('login', App\Livewire\Site\LoginController::class)->name('login');
Route::get('register', App\Livewire\Site\RegisterController::class)->name('register');


/* Rutas del admin */