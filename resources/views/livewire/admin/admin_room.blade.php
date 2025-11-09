@section('title', 'Administrar salones')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-blue-50 via-white to-cyan-50 min-h-screen">
        <!-- modales -->
        <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar salón' : 'Agregar salón' }}</h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Descripción del salón
                            </label>
                            <input wire:model="fields.description" type="text" id="description"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                            @error('fields.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Capacidad máxima de estudiantes
                            </label>
                            <input wire:model="fields.max_students" type="number" id="max_students" min="1"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                            @error('fields.max_students')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Estado del salón
                            </label>
                            <select wire:model="fields.status" id="status"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                                <option value="1">DISPONIBLE</option>
                                <option value="0">INHABILITADO</option>
                            </select>
                            @error('fields.status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
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
        <!-- fin modales -->

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">

                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Administrar salones</h1>
                        <p class="text-gray-600">Gestión de salones disponibles para clases</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar salón
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-cyan-600 to-cyan-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-vr-cardboard mr-3"></i>
                            Lista de Salones
                        </h2>
                    </div>
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search"
                            type="text"
                            placeholder="Buscar salón..."
                            class="w-full rounded-lg bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Máx. Estudiantes
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($data as $row)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->description }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->max_students }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start justify-start gap-2">
                                                @if ($row->status == 0)
                                                    <button class="p-1 m-1 btn btn-danger" title="Editar" wire:click="estado({{ $row->id }})">
                                                        Inactivo
                                                    </button>
                                                @else
                                                    <button class="p-1 m-1 btn btn-success" title="Editar" wire:click="estado({{ $row->id }})">
                                                        Activo
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" wire:click="abrirModal({{ $row->id }})" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" wire:click="confirmarEliminar({{ $row->id }})" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4h-6v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" wire:click="abrirEquiposVr({{ $row->id }})" title="Equipos">
                                                    <i class="fas fa-vr-cardboard mr-3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            No hay salones registrados aún
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $data->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>

    @include('layouts.components.footer-global')

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('cerrar-modal', modal => closeModal(document.getElementById(modal[0].modal)));
            Livewire.on('abrir-modal', modal => {
                let m = document.getElementById(modal[0].modal);
                if (m) {
                    openModal(m);
                    document.getElementById('description').value = modal[0].fields.description;
                    document.getElementById('max_students').value = modal[0].fields.max_students;
                    document.getElementById('status').value = modal[0].fields.status;
                }
            });
            Livewire.on('swal:success', e => Swal.fire({ icon: 'success', title: '¡Éxito!', text: e.message }));
            Livewire.on('confirmar-eliminar', data => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then(result => result.isConfirmed && Livewire.find(@this.id).call('erase', data[0].id));
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
