@section('title', "Eventos")

<main>
    <!-- modales -->
    <div id="modal-home" class="modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar usuario' : 'Nuevo usuario' }}</h5>
                    <button type="button" class="btn-close bg-white/20 hover:bg-white/30 border-0" aria-label="Cerrar"
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
                    <button type="button" class="btn btn-warning bg-yellow-600 hover:bg-yellow-700 text-white border-0" wire:click="update" wire:loading.attr="disabled" wire:target="update">
                        <span wire:loading.remove wire:target="update">Actualizar</span>
                        <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                    </button>
                    @else
                    <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" wire:click="store" wire:loading.attr="disabled" wire:target="store">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary bg-white/10 hover:bg-white/20 text-white border border-white/20"
                        onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modales -->

    <!-- Contenido - inicio -->
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-gray-100 via-gray-50 to-white py-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-balance mb-4">
                Eventos y <span class="text-blue-500">Actividades</span>
            </h1>
            <p class="text-white/60 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                Participa en congresos, talleres, seminarios y actividades diseñadas para impulsar tu desarrollo
                académico y profesional.
            </p>
        </div>
    </div>

    <div class="w-full pb-8">
        <!-- Búsqueda y Filtros -->
        <div class=" bg-white/5rounded-lg shadow-sm p-6 mb-8 relative z-10">
            <div class="flex flex-col lg:flex-row gap-4 items-center">
                <!-- Búsqueda -->
                <div class="relative w-full lg:w-72">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live="search" placeholder="Buscar eventos..."
                        class="w-full pl-12 pr-4 py-3 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Filtro Tipo de Evento -->
                <div class="w-full lg:w-auto">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <select wire:model.live="tipo_evento"
                            class="w-full lg:w-56 pl-12 pr-10 py-3 border border-white/20 rounded-lg bg-white/5 text-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tipo de evento</option>
                            <option value="congreso">Congreso</option>
                            <option value="taller">Taller</option>
                            <option value="seminario">Seminario</option>
                            <option value="conferencia">Conferencia</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Filtro Modalidad -->
                <div class="w-full lg:w-auto">
                    <div class="relative">
                        <select wire:model.live="modalidad"
                            class="w-full lg:w-48 pl-4 pr-10 py-3 border border-white/20 rounded-lg bg-white/5 text-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Modalidad</option>
                            <option value="presencial">Presencial</option>
                            <option value="virtual">Virtual</option>
                            <option value="hibrido">Híbrido</option>
                        </select>
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Contador -->
                <div class="w-full lg:w-auto text-center lg:text-right ml-auto">
                    <p class="text-sm text-white leading-5 dark:text-white/40">
                        <span>Mostrando</span>
                        <span class="font-medium">{{ $records->firstItem() ?? 0 }}</span>
                        <span>de</span>
                        <span class="font-medium">{{ $records->lastItem() ?? 0 }}</span>
                        <span>de</span>
                        <span class="font-medium">{{ $records->total() ?? 0 }}</span>
                        <span>resultados</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Pestañas de Eventos -->
        <div class="flex justify-center mb-10">
            <div class="inline-flex shadow-md rounded-[10px]">
                <button wire:click="$set('tab', 'proximos')"
                    class="px-12 py-2 text-sm font-medium text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-l-[10px]">
                    Próximos Eventos
                </button>
                <button wire:click="$set('tab', 'pasados')"
                    class="px-12 py-2 text-sm font-medium text-white/60 bg-white/5 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-r-[10px]">
                    Eventos Pasados
                </button>
            </div>
        </div>

        <!-- Título de Sección -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white">Todos los Eventos</h2>
        </div>
    </div>
    <!-- Contenido - fin -->
</main>

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
                '¿Estas seguro de eliminar este Eventos?',
                'warning',
                'Si, eliminar',
                'Cancelar'
            )) {
                Livewire.dispatch('delete', { id });
            }
        }
</script>