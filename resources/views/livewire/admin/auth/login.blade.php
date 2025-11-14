@extends('layouts.loginAndRegister')

@section('title', 'Iniciar sesión')

{{-- Ocultar menú / header / footer en login --}}
@section('hide_menu', true)
@section('hide_header', true)
@section('hide_footer', true)

@section('content')
<div  class="min-h-screen flex items-center justify-center px-4 relative" id="loginForm">    {{-- Overlay de carga Livewire --}}
    <div wire:loading.flex wire:target="login"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm items-center justify-center">
        <div class="flex flex-col items-center gap-3 rounded-2xl bg-slate-900/90 border border-sky-500/40 px-6 py-5 shadow-2xl">
            <div class="h-10 w-10 border-2 border-sky-400 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs text-slate-200 tracking-wide">
                Iniciando sesión, por favor espera...
            </p>
        </div>
    </div>

    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 rounded-[32px]
                border border-slate-800/70 bg-[#050816]/90
                shadow-[0_28px_80px_rgba(15,23,42,0.95)] overflow-hidden relative z-10">

        {{-- IZQUIERDA: Card de login --}}
        <div class="flex items-center justify-center px-8 lg:px-10 py-12">
            <div class="w-full max-w-sm text-black">

                {{-- LOGO --}}
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 rounded-2xl bg-sky-500/90 flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/>
                            <path d="M10 18h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-[0.2em] text-sky-400 uppercase">
                            Minerva Labs
                        </p>
                        <p class="text-[11px] text-slate-400">Laboratorio de Realidad Virtual</p>
                    </div>
                </div>

                {{-- TÍTULO --}}
                <div class="mb-6">
                    <h1 class="text-3xl font-semibold leading-snug text-slate-50">
                        Bienvenido de nuevo
                    </h1>
                    <p class="mt-3 text-sm text-slate-400">
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('register') }}"
                           class="font-medium text-sky-400 hover:text-sky-300 transition-colors">
                            Crear cuenta
                        </a>
                    </p>
                </div>

                {{-- ERRORES DE VALIDACIÓN (Laravel) --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-500/50 bg-red-500/10 px-4 py-3">
                        <div class="flex items-start gap-2">
                            <div class="mt-1 h-2 w-2 rounded-full bg-red-400"></div>
                            <div>
                                <p class="text-xs font-semibold text-red-200">
                                    Hay problemas con los datos ingresados:
                                </p>
                                <ul class="mt-1 text-xs text-red-300 space-y-0.5 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- MENSAJE DE ÉXITO (por si viene de registro o logout) --}}
                @if (session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3">
                        <p class="text-xs text-emerald-200">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- ERROR DE LOGIN (credenciales / usuario inactivo) --}}
                @if ($loginError)
                    <div class="mb-4 rounded-2xl border border-red-500/50 bg-red-500/10 px-4 py-3">
                        <p class="text-xs text-red-200">{{ $loginError }}</p>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <form wire:submit.prevent="login" class="space-y-5">

                    {{-- Usuario --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Usuario</label>
                        <input wire:model.defer="username" type="text"
                               placeholder="Tu usuario"
                               onkeyup="this.value = this.value.toLowerCase();"
                               class="w-full h-11 rounded-xl border border-slate-700/70 bg-[#020617]/80 px-4
                                      text-sm text-black placeholder-slate-500 outline-none
                                      focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                      @error('username') border-red-500/70 @enderror">
                @error('username')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Contraseña</label>
                        <input wire:model.defer="password" type="password"
                               placeholder="••••••••"
                               class="w-full h-11 rounded-xl border border-slate-700/70 bg-[#020617]/80 px-4
                                      text-sm text-black placeholder-slate-500 outline-none
                                      focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                      @error('password') border-red-500/70 @enderror">
                @error('password')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

                    {{-- Remember + Olvidé contraseña --}}
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" wire:model="remember_me"
                                   class="h-3.5 w-3.5 rounded border-slate-600 bg-slate-900 text-sky-500
                                          focus:ring-sky-500/60">
                            <span>Recuérdame</span>
                </label>

                        <a href="#" class="text-sky-400 hover:text-sky-300">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- BOTÓN --}}
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>


                    {{-- Volver al sitio --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('home-site') }}"
                           class="text-xs text-sky-400 hover:text-sky-300 transition-colors">
                            ← Volver al sitio
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- DERECHA: Panel informativo / hero --}}
        <div class="hidden lg:flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 p-12">
            <div class="max-w-md text-black">

                <span class="inline-flex items-center px-4 py-1 rounded-full bg-sky-500/10 border border-sky-500/40
                             text-[11px] font-medium text-sky-300 mb-6">
                    Laboratorio de Realidad Virtual
                </span>

                <h2 class="text-2xl font-semibold leading-snug text-sky-300 mb-3">
                    El Futuro de la Educación Inmersiva
                </h2>

                <p class="text-sm text-slate-300 leading-relaxed mb-3">
                    Descubre experiencias educativas con realidad virtual de vanguardia.
                    Gestiona reservas, proyectos y recursos de Minerva Labs desde una sola plataforma.
                </p>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Diseñado para la comunidad de Ingeniería en Sistemas Informáticos de la FMO,
                    promoviendo la innovación, la experimentación y el aprendizaje activo.
                </p>
            </div>
        </div>
    </div>