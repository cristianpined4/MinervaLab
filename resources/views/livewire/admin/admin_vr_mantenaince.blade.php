@section('title', 'Administrar Mantenimiento')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row min-h-screen">
        <!-- modales -->
        <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar Mantenimiento' : 'Agregar Mantenimiento' }}</h5>
                        <button type="button" class="btn-close bg-white/20 hover:bg-white/30 border-0" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Inicio</label>
                            <input wire:model="fields.starts_at" type="date" id="starts_at"
                                class="form-control @error('fields.starts_at') was-validated is-invalid @enderror">
                            @error('fields.starts_at')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Fin</label>
                            <input wire:model="fields.ends_at" type="date" id="ends_at"
                                class="form-control @error('fields.ends_at') was-validated is-invalid @enderror">
                            @error('fields.ends_at')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="fields.description" id="description" rows="3" placeholder="Descripción del mantenimiento"
                                class="form-control @error('fields.description') was-validated is-invalid @enderror"></textarea>
                            @error('fields.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Sala</label>
                            <select wire:model.live="room" id="room" wire:key="room-select-{{ $record_id ?? 'new' }}"
                                class="form-control @error('room') was-validated is-invalid @enderror">
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->id }}">{{ $r->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Equipo (Código)</label>
                            <select wire:model.live="fields.id_vr" id="id_vr" wire:key="vr-select-room-{{ $room ?? 'none' }}-record-{{ $record_id ?? 'new' }}"
                                class="form-control @error('fields.id_vr') was-validated is-invalid @enderror">
                                @forelse ($vr_glasses as $vr)
                                    <option value="{{ $vr->id }}">{{ $vr->code }}</option>
                                @empty
                                    <option value="">No hay equipos disponibles para esta sala</option>
                                @endforelse
                            </select>
                            @error('fields.id_vr')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning bg-yellow-600 hover:bg-yellow-700 text-white border-0" wire:click="store_update" wire:loading.attr="disabled" wire:target="store_update">
                                <span wire:loading.remove wire:target="store_update">Actualizar</span>
                                <span wire:loading wire:target="store_update"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                            </button>
                        @else
                            <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" wire:click="store_update" wire:loading.attr="disabled" wire:target="store_update">
                                <span wire:loading.remove wire:target="store_update">Guardar</span>
                                <span wire:loading wire:target="store_update"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary bg-white/10 hover:bg-white/20 text-white border border-white/20" onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- fin modales -->

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Administrar Mantenimientos</h1>
                        <p class="text-white/60">Gestión de mantenimientos de equipos VR</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Mantenimiento
                        </button>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="bg-white/5 rounded-2xl border border-white/10 shadow-lg overflow-hidden border border-white/10">

                    {{-- Header de la tabla --}}
                    <div class="bg-gradient-to-r from-cyan-600 to-cyan-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-tools mr-3"></i>
                            Lista de Mantenimientos
                        </h2>
                    </div>
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search"
                            type="text"
                            placeholder="Buscar..."
                            class="w-full rounded-lg bg-white/5 text-white placeholder-white/40 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>
                    {{-- Tabla responsive --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5 border-b-2 border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Inicio</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Fin</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Código Equipo</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-white/60 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                @foreach ($data as $row)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-white">{{ $row->starts_at }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-white">{{ $row->ends_at }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-white">{{ $row->description }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-white">{{ $row->vrGlasses->code ?? '—' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button class="p-2 bg-blue-600 hover:bg-blue-700 text-white inline-flex items-center justify-center rounded-lg transition-colors" title="Editar" wire:click="abrirModal({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 bg-red-600 hover:bg-red-700 text-white inline-flex items-center justify-center rounded-lg transition-colors" title="Eliminar" wire:click="confirmarEliminar({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-white/50">No hay registros</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer de la tabla con paginación --}}
                    <div class="bg-white/5 px-6 py-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-sm text-white/60">
                            @if($data->total() > 0)
                                Mostrando <span class="font-semibold text-white">{{ $data->firstItem() }}</span>
                                &ndash; <span class="font-semibold text-white">{{ $data->lastItem() }}</span>
                                de <span class="font-semibold text-white">{{ $data->total() }}</span> registros
                            @else
                                Sin mantenimientos registrados
                            @endif
                        </div>
                        <div>{{ $data->links() }}</div>
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
                if (modalElement) closeModal(modalElement);
            });

            Livewire.on('abrir-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    openModal(modalElement);
                    let starts_at = document.getElementById('starts_at');
                    let ends_at = document.getElementById('ends_at');
                    let description = document.getElementById('description');
                    let room = document.getElementById('room');
                    let id_vr = document.getElementById('id_vr');
                    starts_at.value = modal[0].fields.starts_at;
                    ends_at.value = modal[0].fields.ends_at;
                    description.value = modal[0].fields.description;
                    if (room && modal[0].room !== undefined) room.value = modal[0].room;
                    id_vr.value = modal[0].fields.id_vr;
                }
            });

            Livewire.on('swal:success', e => {
                Swal.fire({ icon: 'success', title: '¡Éxito!', text: e.message });
            });
            Livewire.on('confirmar-eliminar', data => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.find(@this.id).call('erase', data[0].id);
                    }
                });
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
