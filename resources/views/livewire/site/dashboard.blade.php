@section('title', 'Inicio')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="relative z-10 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
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

        <!--  Bienvenida -->
        <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-12 md:py-16 relative">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-3">
                            ¡Hola, {{ auth()->user()?->name ?? 'Usuario' }}!
                        </h1>
                        <p class="text-lg text-blue-100 mb-4">
                            Bienvenido al Sistema de Información MinervaLab
                        </p>
                        <div class="flex items-center gap-4 text-sm text-blue-100">
                            <span><i class="fas fa-calendar mr-1"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</span>
                            <span><i class="fas fa-clock mr-1"></i> {{ now()->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="#eventos" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-colors shadow-lg">
                            <i class="fas fa-calendar-alt mr-2"></i> Ver Eventos
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Eventos Destacados -->
        <section id="eventos" class="max-w-7xl mx-auto px-4 md:px-6 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Próximos Eventos</h2>
                    <p class="text-gray-600">No te pierdas estos eventos destacados</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-semibold hover:underline">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $eventosDestacados = [
                [
                'titulo' => 'Conferencia Anual SIDESI 2025',
                'fecha' => '25 de Octubre, 2025',
                'hora' => '09:00 AM',
                'lugar' => 'Centro de Convenciones',
                'modalidad' => 'Presencial',
                'inscritos' => 150,
                'limite' => 200,
                'imagen' => 'conferencia.jpg',
                'color' => 'blue'
                ],
                [
                'titulo' => 'Taller de Innovación Digital',
                'fecha' => '28 de Octubre, 2025',
                'hora' => '02:00 PM',
                'lugar' => 'Plataforma Virtual',
                'modalidad' => 'Virtual',
                'inscritos' => 80,
                'limite' => 100,
                'imagen' => 'taller.jpg',
                'color' => 'green'
                ],
                [
                'titulo' => 'Seminario de Desarrollo Web',
                'fecha' => '01 de Noviembre, 2025',
                'hora' => '10:00 AM',
                'lugar' => 'Auditorio Principal',
                'modalidad' => 'Presencial',
                'inscritos' => 200,
                'limite' => 250,
                'imagen' => 'seminario.jpg',
                'color' => 'purple'
                ],
                ];
                @endphp

                @foreach($eventosDestacados as $evento)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow group">
                    <div class="h-48 bg-gradient-to-br from-{{ $evento['color'] }}-400 to-{{ $evento['color'] }}-600 relative">
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-{{ $evento['color'] }}-600">
                                {{ $evento['modalidad'] }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <div class="text-3xl font-bold">{{ explode(' ', $evento['fecha'])[0] }}</div>
                            <div class="text-sm">{{ explode(' ', $evento['fecha'])[2] }}</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 group-hover:text-{{ $evento['color'] }}-600 transition-colors">
                            {{ $evento['titulo'] }}
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-{{ $evento['color'] }}-500 w-4"></i>
                                <span>{{ $evento['hora'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-{{ $evento['color'] }}-500 w-4"></i>
                                <span>{{ $evento['lugar'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-users text-{{ $evento['color'] }}-500 w-4"></i>
                                <span>{{ $evento['inscritos'] }}/{{ $evento['limite'] }} inscritos</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $evento['color'] }}-500 h-2 rounded-full transition-all" style="width: {{ ($evento['inscritos'] / $evento['limite']) * 100 }}%"></div>
                            </div>
                        </div>
                        <button wire:click="inscribirEvento({{ $loop->index }})" class="w-full bg-{{ $evento['color'] }}-500 text-white py-3 rounded-xl font-semibold hover:bg-{{ $evento['color'] }}-600 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i> Inscribirse
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </section>



        <!-- Documentos y Recursos -->
        <section class="max-w-7xl mx-auto px-4 md:px-6 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Documentos y Recursos</h2>
                    <p class="text-gray-600">Accede a documentos importantes y materiales de apoyo</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-semibold hover:underline">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $documentos = [
                ['titulo' => 'Manual de Usuario', 'tipo' => 'PDF', 'icon' => 'fa-file-pdf', 'color' => 'red', 'tamaño' => '2.5 MB'],
                ['titulo' => 'Calendario Académico', 'tipo' => 'Excel', 'icon' => 'fa-file-excel', 'color' => 'green', 'tamaño' => '1.2 MB'],
                ['titulo' => 'Reglamento Interno', 'tipo' => 'Word', 'icon' => 'fa-file-word', 'color' => 'blue', 'tamaño' => '850 KB'],
                ['titulo' => 'Formulario de Inscripción', 'tipo' => 'PDF', 'icon' => 'fa-file-pdf', 'color' => 'red', 'tamaño' => '450 KB'],
                ];
                @endphp

                @foreach($documentos as $doc)
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all border border-gray-100 group cursor-pointer">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-{{ $doc['color'] }}-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas {{ $doc['icon'] }} text-{{ $doc['color'] }}-600 text-2xl"></i>
                        </div>
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">{{ $doc['tipo'] }}</span>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2 group-hover:text-{{ $doc['color'] }}-600 transition-colors">
                        {{ $doc['titulo'] }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $doc['tamaño'] }}</p>
                    <button class="w-full bg-gray-100 text-gray-700 py-2 rounded-xl font-semibold hover:bg-{{ $doc['color'] }}-500 hover:text-white transition-colors">
                        <i class="fas fa-download mr-2"></i> Descargar
                    </button>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Estadísticas Personales -->
        <section class="bg-gradient-to-r from-indigo-600 to-blue-600 py-12">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-8 text-center">Tu Actividad</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">{{ $mis_eventos ?? 5 }}</div>
                        <p class="text-blue-100">Eventos Inscritos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">{{ $mis_descargas ?? 12 }}</div>
                        <p class="text-blue-100">Documentos Descargados</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">{{ $mis_certificados ?? 3 }}</div>
                        <p class="text-blue-100">Certificados Obtenidos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-6 text-center">
                        <div class="text-4xl font-bold text-white mb-2">{{ $dias_activo ?? 45 }}</div>
                        <p class="text-blue-100">Días Activo</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección de Ayuda -->
        <section class="max-w-7xl mx-auto px-4 md:px-6 py-12">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-3xl shadow-2xl p-8 md:p-12 text-center text-white">
                <i class="fas fa-graduation-cap text-6xl mb-6 opacity-80"></i>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">¿Necesitas ayuda?</h2>
                <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                    Nuestro equipo de soporte está disponible para ayudarte con cualquier duda o consulta
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-blue-400 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-300 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i> Enviar Correo
                    </a>
                </div>
            </div>
        </section>
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
    </script>

</div>