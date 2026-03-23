@section('title', 'Panel de Administrador')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    {{-- Modal: gestionar clave de asistencia --}}
    <div id="modal-home" class="modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2 text-blue-500"></i>
                        Credenciales de asistencia
                    </h5>
                    <button type="button" class="btn-close bg-white/20 hover:bg-white/30 border-0" onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-white mb-2">
                            Clave de acceso
                            @error('fields.key')
                                <span class="text-red-500 text-sm ms-1">* {{ $message }}</span>
                            @enderror
                        </label>
                        <input wire:model="fields.key" type="text" id="key" placeholder="Ingrese la clave de asistencia"
                            class="w-full border-2 border-white/20 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition uppercase"
                            oninput="this.value = this.value.toUpperCase();">
                        <p class="text-xs text-white/40 mt-1">Mínimo 10 caracteres. Esta clave la usan los docentes para registrar asistencia.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" wire:click="store_update">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary bg-white/10 hover:bg-white/20 text-white border border-white/20" onclick="closeModal(this.closest('.modal'))">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <main class="relative z-10 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 md:px-6 py-8">

            {{-- ── Encabezado ──────────────────────────────────────────── --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-1">Panel de Administrador</h1>
                    <p class="text-white/50 text-sm">Gestiona todos los módulos del sistema desde aquí.</p>
                </div>
                <button
                    wire:click="abrirModal"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg transition-all duration-200 hover:-translate-y-0.5 shrink-0">
                    <i class="fas fa-key"></i>
                    Credenciales de asistencia
                </button>
            </div>

            {{-- ── Stats rápidas ───────────────────────────────────────── --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-users text-blue-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['usuarios'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Usuarios</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-calendar-check text-green-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['reservaciones'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Reservaciones</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-clock text-yellow-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['pendientes'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Pendientes</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-door-open text-cyan-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['salas'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Salas</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-vr-cardboard text-purple-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['escenas'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Escenas VR</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                    <i class="fas fa-glasses text-pink-400 text-xl mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $stats['lentes'] }}</p>
                    <p class="text-xs text-white/40 mt-0.5">Lentes VR</p>
                </div>

            </div>

            {{-- ── Grid de módulos ─────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($opciones as $option)
                <a href="{{ route($option['link']) }}"
                   class="group relative bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col gap-4
                          hover:bg-white/10 hover:border-white/20 hover:shadow-xl hover:shadow-blue-900/30
                          transition-all duration-200 hover:-translate-y-1 overflow-hidden">

                    {{-- Icono --}}
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $option['color'] }}-500 to-{{ $option['color'] }}-700
                                flex items-center justify-center shadow-lg shrink-0">
                        <i class="fa-solid {{ $option['icono'] }} text-white text-lg"></i>
                    </div>

                    {{-- Texto --}}
                    <div>
                        <h3 class="text-base font-semibold text-white mb-1 group-hover:text-{{ $option['color'] }}-300 transition-colors">
                            {{ $option['titulo'] }}
                        </h3>
                        <p class="text-xs text-white/40 leading-relaxed">
                            {{ $option['descripcion'] }}
                        </p>
                    </div>

                    {{-- Flecha --}}
                    <div class="mt-auto flex items-center justify-end">
                        <i class="fas fa-arrow-right text-sm text-white/20 group-hover:text-{{ $option['color'] }}-400 transition-colors"></i>
                    </div>

                </a>
                @endforeach
            </div>

        </div>

    </main>

    @include('layouts.components.footer-global')

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('cerrar-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) closeModal(modalElement);
            });

            Livewire.on('abrir-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    openModal(modalElement);
                    let key = document.getElementById('key');
                    if (key) key.value = modal[0].fields.key ?? '';
                }
            });

            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: e[0].icon ?? 'success',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                });
            });
        });
    </script>

</div>