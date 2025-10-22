@section('title', 'Inicio')
@section('hide_header', true)
@section('hide_footer', true)


<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
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

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">

                {{-- Header Section --}}
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Bienvenido a MinervaLab</h1>
                    <p class="text-gray-600">Mantente al día con las últimas actualizaciones y eventos</p>
                </div>

                {{-- Grid de Noticias --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Tarjeta de Imagen --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative overflow-hidden group">
                            <img src="https://via.placeholder.com/600x400" alt="Noticia 1" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded-full">Actualización</span>
                                <span class="text-gray-400 text-xs">Hace 2 días</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-blue-600 transition-colors">
                                Nueva Actualización del Sistema
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                Se han agregado nuevas funciones que mejoran la velocidad y la experiencia del usuario en el panel.
                            </p>
                            <button class="text-blue-600 font-semibold text-sm hover:text-blue-700 transition-colors flex items-center gap-2">
                                Leer más
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Tarjeta de Video --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative w-full h-64 bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center group cursor-pointer">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
                            <button class="relative z-10 transform transition-all duration-300 group-hover:scale-110">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl">
                                    <svg class="w-10 h-10 text-blue-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </button>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="bg-white/90 backdrop-blur-sm rounded-lg p-2 text-xs text-gray-700 font-semibold">
                                    <i class="fas fa-clock mr-1"></i> 3:45 min
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-purple-100 text-purple-600 text-xs font-semibold rounded-full">Video</span>
                                <span class="text-gray-400 text-xs">Hace 5 días</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-blue-600 transition-colors">
                                Resumen de Actividades
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                Mira el resumen de los eventos más importantes realizados esta semana en MinervaLab.
                            </p>
                            <button class="text-blue-600 font-semibold text-sm hover:text-blue-700 transition-colors flex items-center gap-2">
                                Ver video
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Sección de Estadísticas Rápidas --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Reservaciones</p>
                                <p class="text-2xl font-bold text-gray-800">24</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Eventos</p>
                                <p class="text-2xl font-bold text-gray-800">12</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Usuarios Activos</p>
                                <p class="text-2xl font-bold text-gray-800">156</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Notificaciones</p>
                                <p class="text-2xl font-bold text-gray-800">8</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>

    @include('layouts.components.footer-global')

    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('cerrar-modal', function(modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    closeModal(modalElement);
                }
            });

            Livewire.on('abrir-modal', function(modal) {
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
                Livewire.dispatch('delete', {
                    id
                });
            }
        }

        const btn = document.getElementById('userMenuBtn');
        const menu = document.getElementById('userMenu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
</div>