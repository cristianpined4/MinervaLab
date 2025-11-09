@section('title', 'Administrar Equipo')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
          <!-- modales -->
        <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar Equipo' : 'Agregar Equipo' }}</h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Código</label>
                            <input wire:model="fields.code" type="text" placeholder="Código" id="code"
                                class="form-control @error('fields.code') was-validated is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase();">
                            @error('fields.code')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <div class="invalid-feedback">@error('fields.code') {{$message}} @enderror</div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Fecha de ingreso
                            </label>
                            <input wire:model="fields.entry_date" type="date" id="entry_date"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('fields.entry_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Horas de vida útil</label>
                            <input wire:model="fields.life_hours" type="number" placeholder="Horas de vida útil" id="life_hours"
                                class="form-control @error('fields.life_hours') was-validated is-invalid @enderror">
                            @error('fields.life_hours')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Años útiles</label>
                            <input wire:model="fields.usefull_years" type="number" placeholder="Años útiles" id="usefull_years"
                                class="form-control @error('fields.usefull_years') was-validated is-invalid @enderror">
                            @error('fields.usefull_years')
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
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Administrar equipos</h1>
                        <p class="text-gray-600">Gestión de gafas VR para: {{ $room->description }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Equipo
                        </button>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                    {{-- Header de la tabla --}}
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-vr-cardboard mr-3"></i>
                            Lista de lentes VR
                        </h2>
                    </div>
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search"
                            type="text"
                            placeholder="Buscar..."
                            class="w-full rounded-lg bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>
                    {{-- Tabla responsive --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha ingreso</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Horas de vida</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Años útiles</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($data as $row)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->code }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->entry_date }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->life_hours }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $row->usefull_years }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar" wire:click="abrirModal({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar" wire:click="confirmarEliminar({{ $row->id }})">
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
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay registros</td>
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
            Livewire.on('cerrar-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) closeModal(modalElement);
            });

            Livewire.on('abrir-modal', function (modal) {
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    openModal(modalElement);
                    let code = document.getElementById('code');
                    let entry_date = document.getElementById('entry_date');
                    let life_hours = document.getElementById('life_hours');
                    let usefull_years = document.getElementById('usefull_years');
                    code.value = modal[0].fields.code;
                    entry_date.value = modal[0].fields.entry_date;
                    life_hours.value = modal[0].fields.life_hours;
                    usefull_years.value = modal[0].fields.usefull_years;
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
