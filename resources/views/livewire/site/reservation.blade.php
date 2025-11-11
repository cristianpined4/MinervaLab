@section('title', 'Reservar Escena')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Reservar Aula VR</h1>
                <p class="text-gray-600">Selecciona una escena, fecha y hora para tu reservación</p>
            </div>

            {{-- GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- COLUMNA 1 --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex-row items-center justify-between mb4">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Seleccionar Escenas
                            </h2>
                        </div>

                        <div class="text-sm text-gray-600 mb-3">
                            Tiempo de escenas estimado: <span class="font-bold text-indigo-600">{{ $total_time }} min</span>
                        </div>
                        @if (!empty($warnings))
                            <div class="mb-4 space-y-2">
                                @foreach($warnings as $w)
                                    <div class="text-sm bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                        <strong>Advertencia:</strong> {{ $w }}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- INPUT BUSCAR --}}
                        <div class="relative w-full border-b-2 mb-6">
                            <input wire:model.live="search" type="text" placeholder="Buscar escena..."
                                class="w-full rounded-lg bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                            <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                            </svg>
                        </div>

                        {{-- GRID ESCENAS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach ($scenes as $scene)
                                <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-200 hover:border-indigo-500 transform hover:-translate-y-1">
                                    <div class="h-48  flex items-center justify-center relative overflow-hidden" style="background-color: {{ $scene->sceneCategory->color }}">
                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-all duration-300"></div>
                                        <i class="fas @if ($scene->sceneCategory->icon) {{'fa-' . $scene->sceneCategory->icon}}
                                        @else {{'fa-vr-cardboard'}} @endif text-white text-6xl"></i>
                                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                            <span class="text-xs font-bold text-gray-700">VR Ready</span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $scene->description }}</h3>
                                        <p class="text-sm text-gray-500 mb-3">{{ $scene->sceneCategory->description ?? '' }}</p>

                                        <div class="flex items-center justify-between gap-2">
                                            <button wire:click="verVideo({{ $scene->id }})"
                                                class="bg-gray-100 hover:bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                <i class="fas fa-play-circle mr-1"></i> Vista previa
                                            </button>
                                            @if (in_array($scene->id, $selected_scenes))
                                                <button wire:key="scene-del-{{ $scene->id }}"  wire:click="removeScene({{ $scene->id }})"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                    <i class="fas fa-trash mr-1"></i> Borrar
                                                </button>
                                            @else
                                                <button wire:key="scene-add-{{ $scene->id }}"  wire:click="addScene({{ $scene->id }})"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                    <i class="fas fa-plus mr-1"></i> Agregar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Detalles de la reservación --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Detalles de la Reservación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Selector de sala --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Selecciona una sala
                                    </label>
                                    <select wire:model="room_id"  wire:change='calcTime'  class="w-full text-black border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="">Seleccionar...</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->description }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Duración de escenas
                                    </label>
                                    <div class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 bg-gray-100 text-gray-700">
                                        {{ $total_time }} min
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Número de estudiantes
                                    </label>
                                    <input wire:model.live="numeroEstudiantes" wire:change='calcTime' type="number"
                                        placeholder="Ej: 10"
                                        class="w-full text-black border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <p class="text-xs text-gray-500 mt-1">Máximo {{$maxStudents}} estudiantes por reserva ({{$vrCount}} lentes disponibles en la sala).</p>
                                </div>
                                @if ($sessions > 1)
                                    <div class="mt-2 flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-amber-800">
                                            La reservacion esta sobrecargada, las escenas deberan reproducirse mas de una vez
                                        </p>
                                    </div>
                                @endif
                                {{--
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Notas adicionales (opcional)
                                    </label>
                                    <textarea wire:model="notas" rows="3" placeholder="Objetivos de aprendizaje, requisitos especiales, etc."
                                        class="w-full text-black border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"></textarea>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Fecha y Horario
                        </h2>

                        {{-- Selector de fecha --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Selecciona una fecha
                            </label>
                            <input wire:model="fecha" wire:change='getDispose' type="date"
                                class="w-full text-black border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center justify-between">
                                <span>Horarios disponibles</span>
                                <span class="text-xs font-normal text-gray-500">Selecciona uno</span>
                            </h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach ($disponibilidad as $key => $horario)
                                    <div wire:click="seleccionarHorario('{{ $key }}')"
                                         class="flex items-center justify-between border-2 rounded-xl p-3 transition-all cursor-pointer
                                         {{ $horario['disponible']
                                            ? 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50'
                                            : 'border-gray-100 bg-gray-50 cursor-not-allowed opacity-60' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                {{ $horario['disponible'] ? 'bg-green-100' : 'bg-red-100' }}">
                                                @if($horario['disponible'])
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-800 block">{{ $horario['hora'] }}</span>
                                                @if($horario['disponible'])
                                                    <span class="text-xs text-green-600 font-medium">
                                                        {{ $horario['restantes'] }} {{ $horario['restantes'] == 1 ? 'espacio' : 'espacios' }} disponible{{ $horario['restantes'] == 1 ? '' : 's' }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-red-600 font-medium">No disponible</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $slotStarts = \Carbon\Carbon::parse($horario['starts_at']);
                                                $slotEnds = \Carbon\Carbon::parse($horario['ends_at']);
                                                $slotMinutes = $slotStarts->diffInMinutes($slotEnds);
                                            @endphp
                                            <div class="text-xs text-gray-500">Disponible: {{ $slotMinutes }} min</div>
                                            @if($total_time > $slotMinutes)
                                                <div class="text-xs text-yellow-600 font-semibold">No cabe ({{ $total_time }} min)</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Resumen de la reservación --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 mb-6">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Resumen de Reservación</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-semibold text-gray-800">{{ $fecha ?? '--/--/----' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Tiempo total:</span>
                                    <span class="font-semibold text-gray-800">{{ $total_time }} min</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Reproducciones:</span>
                                    <span class="font-semibold text-gray-800">{{ $sessions }} Rep.</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Horario:</span>
                                    <span class="font-semibold text-gray-800">
                                        @if($horarioSeleccionado)
                                            {{ $horarioSeleccionado['label'] ?? '--:--' }}
                                            ( {{ $horarioSeleccionado['starts_at'] }} - {{ $horarioSeleccionado['ends_at'] }} )
                                        @else
                                            --:-- -- - --:-- --
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Estudiantes:</span>
                                    <span class="font-semibold text-gray-800">{{ $numeroEstudiantes }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Botón de acción --}}
                        <button wire:click="confirmarReservacion"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Confirmar Reservación
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-3">
                            Recibirás un correo de confirmación. Estado de la reserva <strong>PENDIENTE</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL VIDEO --}}
    <div id="modal-video" class="modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-b border-gray-700 text-white">
                    <h2 class="text-xl font-bold text-black flex items-center gap-2" id="video_title">Vista previa</h2>
                    <button type="button" class="btn btn-close text-black text-3xl" aria-label="Cerrar"
                        onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>
                <div class="modal-body p-0">
                    <video id="video_player" class="w-full h-auto" controls>
                        <source id="video_source" src="" type="video/mp4">
                        Tu navegador no soporta reproducción de video.
                    </video>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.components.footer-global')

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('abrir-video', data => {
                const modalId = data[0].modal;
                const videoUrl = data[0].videoUrl;
                setTimeout(() => {
                    const modal = document.getElementById(modalId);
                    const video = document.getElementById('video_player');
                    const source = document.getElementById('video_source');
                    const title = document.getElementById('video_title');
                    if (modal && video && source) {
                        source.src = videoUrl;
                        title.textContent = data[0].description || 'Vista previa';
                        video.load();
                        openModal(modal);
                    }
                }, 200);
            });
            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: e[0].icon || 'success',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            });
            Livewire.on('swal:success', e => {
                Swal.fire({
                    icon: 'success',
                    title: e[0].message
                });
            });
        });
    </script>
</div>
