@extends('layouts.loginAndRegister')

@section('title', 'Crear una cuenta')

@section('content')
<div class="min-h-screen flex items-start justify-center px-4 pt-20 pb-10 relative">

    {{-- Overlay de carga Livewire --}}
    <div wire:loading.flex wire:target="store"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm items-center justify-center">
        <div class="flex flex-col items-center gap-3 rounded-2xl bg-slate-900/90 border border-sky-500/40 px-6 py-5 shadow-2xl">
            <div class="h-10 w-10 border-2 border-sky-400 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs text-slate-200 tracking-wide">
                Creando tu cuenta, por favor espera...
            </p>
        </div>
    </div>

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 rounded-3xl border border-slate-700/60
                bg-slate-900/70 shadow-[0_24px_70px_rgba(15,23,42,0.9)] overflow-hidden relative z-10">

        {{-- IZQUIERDA --}}
        <div class="flex items-center justify-center px-8 lg:px-10 py-12">
            <div class="w-full max-w-md text-black">

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
                        Crear una cuenta
                    </h1>
                    <p class="mt-3 text-sm text-slate-400">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}"
                           class="font-medium text-sky-400 hover:text-sky-300 transition-colors">
                            Iniciar sesión
                        </a>
                    </p>
                </div>

                {{-- ALERTA DE ERRORES GLOBALES --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-500/50 bg-red-500/10 px-4 py-3">
                        <div class="flex items-start gap-2">
                            <div class="mt-0.5 h-2 w-2 rounded-full bg-red-400"></div>
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

                {{-- ALERTA ÉXITO --}}
                @if (session('success'))
                    <div class="mb-5 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3">
                        <p class="text-xs text-emerald-200">
                            {{ session('success') }}
                        </p>
                    </div>
                @endif

                {{-- ALERTA ERROR GENERAL (por si usas session('error') en el controlador) --}}
                @if (session('error'))
                    <div class="mb-5 rounded-2xl border border-red-500/50 bg-red-500/10 px-4 py-3">
                        <p class="text-xs text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <form wire:submit.prevent="store" class="space-y-5">

                    {{-- NOMBRE + APELLIDO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Nombre</label>
                            <input wire:model.defer="fields.first_name" type="text"
                                   placeholder="Nombre"
                                   class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                          text-sm text-black placeholder-slate-500 outline-none
                                          focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                          @error('fields.first_name') border-red-500/70 @enderror">
                            @error('fields.first_name')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Apellido</label>
                            <input wire:model.defer="fields.last_name" type="text"
                                   placeholder="Apellido"
                                   class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                          text-sm text-black placeholder-slate-500 outline-none
                                          focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                          @error('fields.last_name') border-red-500/70 @enderror">
                            @error('fields.last_name')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- USUARIO --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Nombre de usuario</label>
                        <input wire:model.defer="fields.username" type="text"
                               placeholder="Nombre de usuario"
                               onkeyup="this.value = this.value.toLowerCase();"
                               class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                      text-sm text-black placeholder-slate-500 outline-none
                                      focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                      @error('fields.username') border-red-500/70 @enderror">
                        @error('fields.username')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CORREO --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Correo electrónico</label>
                        <input wire:model.defer="fields.email" type="email"
                               placeholder="correo@ejemplo.com"
                               onkeyup="this.value = this.value.toLowerCase();"
                               class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                      text-sm text-black placeholder-slate-500 outline-none
                                      focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                      @error('fields.email') border-red-500/70 @enderror">
                        @error('fields.email')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONTRASEÑA + CONFIRMAR --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Contraseña</label>
                            <input wire:model.defer="fields.password" type="password"
                                   placeholder="••••••••"
                                   class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                          text-sm text-black placeholder-slate-500 outline-none
                                          focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                          @error('fields.password') border-red-500/70 @enderror">
                            @error('fields.password')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-2">Confirmar contraseña</label>
                            <input wire:model.defer="other_fields.password_confirmation" type="password"
                                   placeholder="••••••••"
                                   class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                          text-sm text-black placeholder-slate-500 outline-none
                                          focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                          @error('other_fields.password_confirmation') border-red-500/70 @enderror">
                            @error('other_fields.password_confirmation')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- TELÉFONO --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Teléfono</label>
                        <input wire:model.defer="fields.phone" type="text"
                               placeholder="7000-0000"
                               class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                      text-sm text-black placeholder-slate-500 outline-none
                                      focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                      @error('fields.phone') border-red-500/70 @enderror">
                        @error('fields.phone')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FACULTAD --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-2">Facultad</label>
                        <select wire:model.defer="fields.id_faculty"
                                class="w-full h-11 rounded-xl border border-slate-300 bg-white/95 px-4
                                       text-sm text-black outline-none
                                       focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40
                                       @error('fields.id_faculty') border-red-500/70 @enderror">
                            <option value="">Seleccione</option>
                            @forelse(($facultys ?? []) as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                            @empty
                                <option value="" disabled>No hay facultades registradas</option>
                            @endforelse
                        </select>
                        @error('fields.id_faculty')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BOTÓN --}}
                    <button type="submit"
                            wire:target="store"
                            wire:loading.attr="disabled"
                            class="w-full h-11 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-400
                                   hover:from-sky-400 hover:to-cyan-300 text-sm font-medium text-white
                                   shadow-[0_10px_30px_rgba(56,189,248,0.45)] transition-all
                                   disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">

                        {{-- Texto normal --}}
                        <span wire:loading.remove wire:target="store">
                            Crear cuenta
                        </span>

                        {{-- Estado cargando --}}
                        <span wire:loading.flex wire:target="store" class="items-center gap-2">
                            <span class="h-4 w-4 border-2 border-white/70 border-t-transparent rounded-full animate-spin"></span>
                            <span class="text-xs">Guardando información...</span>
                        </span>
                    </button>

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

        {{-- DERECHA: Panel informativo --}}
        <div class="hidden lg:flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 p-12">
            <div class="max-w-md text-slate-100">
                <span class="inline-flex items-center px-4 py-1 rounded-full bg-sky-500/10 border border-sky-500/40
                             text-[11px] font-medium text-sky-300 mb-6">
                    Laboratorio de Realidad Virtual
                </span>

                <h2 class="text-2xl font-semibold leading-snug text-sky-300 mb-3">
                    Conoce Minerva Labs
                </h2>

                <p class="text-sm text-slate-300 leading-relaxed mb-3">
                    Crea tu cuenta para acceder a reservas, proyectos, experiencias inmersivas y herramientas avanzadas de innovación VR.
                </p>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Diseñado para la comunidad de Ingeniería en Sistemas Informáticos de la FMO, promoviendo la innovación,
                    la experimentación y el aprendizaje activo.
                </p>
            </div>
        </div>
    </div>
