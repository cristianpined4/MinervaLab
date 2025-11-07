@section('title', 'Administrar escenas')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

        <!-- Modal -->
        <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $record_id ? 'Editar escena' : 'Agregar escena' }}</h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="form-label">Categoría</label>
                            <select wire:model="fields.id_scene_category" class="form-control" id="id_scene_category">
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->description }}</option>
                                @endforeach
                            </select>
                            @error('fields.id_scene_category')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Descripción</label>
                            <input wire:model="fields.description" type="text" placeholder="Descripción"
                                id="description"
                                class="form-control @error('fields.description') was-validated is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase();">
                            @error('fields.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Duración</label>
                            <input wire:model="fields.duration" type="number" step="0.01"
                                placeholder="Duración (minutos)" id="duration"
                                class="form-control @error('fields.duration') was-validated is-invalid @enderror">
                            @error('fields.duration')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Video de demostración</label>
                            <input wire:model="fields.resource_demo" type="file" accept="video/*"
                                class="form-control @error('fields.resource_demo') was-validated is-invalid @enderror">
                            @error('fields.resource_demo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @if ($record_id && isset($fields['resource_demo']) && $fields['resource_demo'])
                                <video controls class="mt-3 rounded-lg w-full">
                                    <source src="{{ asset($fields['resource_demo']) }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning" wire:click="store_update">Actualizar</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store_update">Guardar</button>
                        @endif
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
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
        <!-- Fin Modal -->

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-6xl mx-auto">

                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Administrar escenas</h1>
                        <p class="text-gray-600">Gestión de escenas y videos de demostración</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin-scene-category') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            Gestionar categorias
                        </a>
                        <button wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar Escena
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Lista de Escenas
                        </h2>
                    </div>

                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search" type="text" placeholder="Buscar escena..."
                            class="w-full rounded-lg bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Duración</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Color</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($data as $row)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $row->sceneCategory->description ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $row->description }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $row->duration }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full border border-gray-300"
                                                    style="background-color: {{ $row->sceneCategory->color }}"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Editar" wire:click="abrirModal({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Eliminar" wire:click="confirmarEliminar({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Ver video" wire:click="verVideo({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14.752 11.168l-5.197-2.6A1 1 0 008 9.47v5.06a1 1 0 001.555.832l5.197-2.6a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            No hay escenas registradas
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
                let el = document.getElementById(modal[0].modal);
                if(el) {
                    openModal(el);
                    document.getElementById('id_scene_category').value = modal[0].fields.id_scene_category;
                    document.getElementById('description').value = modal[0].fields.description;
                    document.getElementById('duration').value = modal[0].fields.duration;
                };
            });
            Livewire.on('confirmar-eliminar', data => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then(result => {
                    if (result.isConfirmed) Livewire.find(@this.id).call('erase', data[0].id);
                });
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
                    } else {
                        console.warn('No se encontró el modal o los elementos del video.');
                    }
                }, 200);
            });



        });
    </script>
</div>
