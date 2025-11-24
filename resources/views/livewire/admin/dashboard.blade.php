@section('title', 'Inicio')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="relative z-10 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
                <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar exclusion' : 'Agregar exclusion' }}</h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Clave de acceso</label>
                            <input wire:model="fields.key" type="text" placeholder="Clave" id="key"
                                class="form-control @error('fields.key') was-validated is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase();">
                            @error('fields.key')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror                            <div class="invalid-feedback">@error('fields.key') {{$message}} @enderror</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning" wire:click="store_update">Actualizar</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store_update">Guardar</button>
                        @endif
                        <button type="button" class="btn btn-secondary" onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funciones del sistema -->
        <section id="noticias" class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Panel de administrador</h2>
                        <p class="text-gray-600"></p>
                    </div>
                    <div>

                        <button
                            wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-clock mr-3 text-white-300"></i>
                            Gestionar Creedenciales de asistencia
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($opciones as $option)
                    <a href="{{ route($option['link']) }}"
                    class="block bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all group">
                        <div class="flex flex-col sm:flex-row">
                            <div class="sm:w-1/4 flex items-center justify-center h-48 sm:h-auto bg-gradient-to-br from-{{ $option['color'] }}-400 to-{{ $option['color'] }}-600">
                                <i class="fa-solid {{ $option['icono'] }} text-white text-3xl"></i>
                            </div>
                            <div class="sm:w-3/4 p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-{{ $option['color'] }}-600 transition-colors">
                                    {{ $option['titulo'] }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $option['descripcion'] }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>
            </div>
        </section>



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
                //alert(JSON.stringify(modal))
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    openModal(modalElement);
                    let key = document.getElementById('key');
                    key.value = modal[0].fields.key
                }
            });

            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            });
    });

    </script>

</div>