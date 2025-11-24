@extends('layouts.loginAndRegister')

@section('title', 'Registrar asistencia')

@section('hide_menu', true)
@section('hide_header', true)
@section('hide_footer', true)

@section('content')
<div  class="min-h-screen flex items-center justify-center px-4 relative" id="loginForm">    {{-- Overlay de carga Livewire --}}

    {{-- Overlay carga --}}
    <div wire:loading.flex wire:target="register"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm items-center justify-center">
        <div class="flex flex-col items-center gap-3 rounded-2xl bg-slate-900/90 border border-sky-500/40 px-6 py-5 shadow-2xl">
            <div class="h-10 w-10 border-2 border-sky-400 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs text-slate-200 tracking-wide">
                Registrando asistencia...
            </p>
        </div>
    </div>

    <div class="w-full max-w-5xl grid grid-cols-1 rounded-[32px]
                border border-slate-800/70 bg-[#050816]/90
                shadow-[0_28px_80px_rgba(15,23,42,0.95)] overflow-hidden relative z-10">

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
                        <p class="text-xs font-semibold tracking-[0.2em] text-sky-400 uppercase">Minerva Labs</p>
                        <p class="text-[11px] text-slate-400">Laboratorio de Realidad Virtual</p>
                    </div>
                </div>

                {{-- TÍTULO --}}
                <h1 class="text-3xl font-semibold leading-snug text-slate-50 mb-6">Control de asistencia</h1>

                {{-- Datos de reservación --}}
                @if ($reservation)
                    <div class="mb-6 rounded-2xl border border-sky-500/40 bg-sky-500/10 px-4 py-3">
                        <p class="text-sm text-sky-200 font-semibold mb-1">Reservación detectada</p>
                        <p class="text-xs text-slate-300">Laboratorio: <span class="text-white">{{ $reservation->id_room }}</span></p>
                        <p class="text-xs text-slate-300">Fecha: <span class="text-white">{{ $reservation->date }}</span></p>
                        <p class="text-xs text-slate-300">
                            Horario:
                            <span class="text-white">{{ $reservation->starts_at }}</span> -
                            <span class="text-white">{{ $reservation->ends_at }}</span>
                        </p>
                        <p class="text-xs text-slate-300">Estudiantes: <span class="text-white">{{ $reservation->students }}</span></p>
                    </div>
                @endif

                {{-- ERROR --}}
                @if ($error)
                    <div class="mb-4 rounded-2xl border border-red-500/50 bg-red-500/10 px-4 py-3">
                        <p class="text-xs text-red-200">{{ $error }}</p>
                    </div>
                @endif

                {{-- MENSAJE --}}
                @if (session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3">
                        <p class="text-xs text-emerald-200">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <div class="space-y-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Carnet del estudiante</label>
                        <input wire:model="carnet" type="text"
                               placeholder="Ej: AB12345"
                               class="w-full h-11 rounded-xl border border-slate-700/70 bg-[#020617]/80 px-4
                               text-sm placeholder-slate-500 outline-none
                               focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                               @error('carnet') border-red-500/70 @enderror">
                        @error('carnet')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="button" wire:click="register" class="btn btn-primary">Registrar asistencia</button>
                </div>

            </div>
        </div>
    </div>

    <script>

        document.addEventListener('livewire:initialized', function () {
            Livewire.on('swal:notify', e => {
                location.href = e[0].url;
            });
        });
    </script>
