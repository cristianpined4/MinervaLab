@section('title', 'Reservar Escena')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen py-8 px-4 md:px-8">
            <!-- modales -->
    <div id="modal-home" class="modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar usuario' : 'Nuevo usuario' }}</h5>
                    <button type="button" class="btn-close" aria-label="Cerrar"
                        onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre Completo</label>
                        <input wire:model="fields.name" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('fields.name') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback">@error('fields.name') {{$message}} @enderror</div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($record_id)
                    <button type="button" class="btn btn-warning" wire:click="update">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="store">Guardar</button>
                    @endif
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modales -->
        <div class="max-w-7xl mx-auto">
            
            {{-- Header con breadcrumb --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Reservar Escena Multimedia</h1>
                <p class="text-gray-600">Selecciona una escena, fecha y hora para tu reservación</p>
            </div>

            {{-- Grid principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- COLUMNA 1: Selección de Escenas --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Seleccionar Escena
                            </h2>
                            <span class="text-sm text-gray-500">4 disponibles</span>
                        </div>

                        {{-- Grid de escenas mejorado --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach ([
                                ['name' => 'Laboratorio Virtual', 'icon' => 'flask', 'color' => 'from-blue-400 to-blue-600'],
                                ['name' => 'Anatomía 3D', 'icon' => 'user-md', 'color' => 'from-green-400 to-green-600'],
                                ['name' => 'Física Interactiva', 'icon' => 'atom', 'color' => 'from-purple-400 to-purple-600'],
                                ['name' => 'Historia Inmersiva', 'icon' => 'landmark', 'color' => 'from-orange-400 to-orange-600']
                            ] as $escena)
                                <div wire:click="seleccionarEscena('{{ $escena['name'] }}')" 
                                     class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-200 hover:border-indigo-500 cursor-pointer transform hover:-translate-y-1 group">
                                    <div class="h-48 bg-gradient-to-br {{ $escena['color'] }} flex items-center justify-center relative overflow-hidden">
                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-all duration-300"></div>
                                        <i class="fas fa-{{ $escena['icon'] }} text-white text-6xl relative z-10 group-hover:scale-110 transition-transform duration-300"></i>
                                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                            <span class="text-xs font-bold text-gray-700">VR Ready</span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-indigo-600 transition">
                                            {{ $escena['name'] }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-3">Simulación interactiva inmersiva</p>
                                        <div class="flex items-center justify-between text-xs text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                45-60 min
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Hasta 30
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Detalles de la reservación --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Detalles de la Reservación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Número de estudiantes
                                    </label>
                                    <input wire:model="numeroEstudiantes" type="number" min="1" max="30" placeholder="Ej: 25"
                                        class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <p class="text-xs text-gray-500 mt-1">Máximo 30 estudiantes por sesión</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Duración estimada
                                    </label>
                                    <select wire:model="duracion" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="">Seleccionar...</option>
                                        <option value="30">30 minutos</option>
                                        <option value="45">45 minutos</option>
                                        <option value="60">1 hora</option>
                                        <option value="90">1.5 horas</option>
                                        <option value="120">2 horas</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Notas adicionales (opcional)
                                    </label>
                                    <textarea wire:model="notas" rows="3" placeholder="Objetivos de aprendizaje, requisitos especiales, etc."
                                        class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA 2: Calendario y Disponibilidad --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Fecha y Horario
                        </h2>

                        {{-- Selector de fecha mejorado --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Selecciona una fecha
                            </label>
                            <input wire:model="fecha" type="date"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <div class="mt-2 flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-amber-800">
                                    Los días feriados y fines de semana están deshabilitados automáticamente.
                                </p>
                            </div>
                        </div>

                        {{-- Disponibilidad de horarios mejorada --}}
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center justify-between">
                                <span>Horarios disponibles</span>
                                <span class="text-xs font-normal text-gray-500">Selecciona uno</span>
                            </h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach ([
                                    ['hora' => '07:00 AM - 09:00 AM', 'disponible' => true, 'restantes' => 2],
                                    ['hora' => '09:30 AM - 11:30 AM', 'disponible' => false],
                                    ['hora' => '12:00 PM - 02:00 PM', 'disponible' => true, 'restantes' => 1],
                                    ['hora' => '02:30 PM - 04:30 PM', 'disponible' => true, 'restantes' => 3],
                                    ['hora' => '05:00 PM - 07:00 PM', 'disponible' => false]
                                ] as $horario)
                                    <div wire:click="seleccionarHorario('{{ $horario['hora'] }}')" 
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
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Resumen de la reservación --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 mb-6">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Resumen de Reservación</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Escena:</span>
                                    <span class="font-semibold text-gray-800">No seleccionada</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-semibold text-gray-800">--/--/----</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Horario:</span>
                                    <span class="font-semibold text-gray-800">--:-- -- - --:-- --</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Estudiantes:</span>
                                    <span class="font-semibold text-gray-800">0</span>
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
                            Recibirás un correo de confirmación
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </main>

    @include('layouts.components.footer-global')

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('cerrar-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    closeModal(modalElement);
            }
        });

        Livewire.on('abrir-modal', function (modal) {
            let modalElement = document.getElementById(modal[0].modal);
            if (modalElement) {
                openModal(modalElement);
            }
        });
    });

    const confirmarEliminar = async id => {
        if (await window.Confirm(
            'Eliminar',
            '¿Estas seguro de eliminar este Home?',
            'warning',
            'Si, eliminar',
            'Cancelar'
        )) {
            Livewire.dispatch('delete', { id });
        }
    }
    </script>
</div>