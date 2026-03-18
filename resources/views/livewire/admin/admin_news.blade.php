@section('title', 'Administrar noticias')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

        {{-- ============================================================
        |  MODAL: Crear / Editar noticia
        ============================================================ --}}
        <div id="modal-news" class="modal" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $record_id ? 'Editar noticia' : 'Nueva noticia' }}
                        </h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>

                    <div class="modal-body">

                        {{-- Tipo de recurso --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Tipo de recurso</label>
                            <select wire:model.live="fields.resource_type" id="resource_type" class="form-control">
                                <option value="article">Artículo</option>
                                <option value="image">Imagen</option>
                                <option value="video">Video</option>
                            </select>
                            @error('fields.resource_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Título --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Título</label>
                            <input wire:model="fields.title" type="text" placeholder="Título de la noticia"
                                class="form-control @error('fields.title') is-invalid @enderror">
                            @error('fields.title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="fields.description" rows="3"
                                placeholder="Descripción breve..."
                                class="form-control @error('fields.description') is-invalid @enderror"></textarea>
                            @error('fields.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Fecha de publicación</label>
                            <input wire:model="fields.date" type="date"
                                class="form-control @error('fields.date') is-invalid @enderror">
                            @error('fields.date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Archivo (imagen o video) - solo si no es artículo --}}
                        @if ($fields['resource_type'] !== 'article')
                            <div class="form-group mb-3">
                                <label class="form-label">
                                    @if ($fields['resource_type'] === 'video') Video @else Imagen @endif
                                    <span class="text-gray-400 text-xs">(máx. 50 MB)</span>
                                </label>
                                <input wire:model="upload" type="file"
                                    accept="{{ $fields['resource_type'] === 'video' ? 'video/*' : 'image/*' }}"
                                    class="form-control @error('upload') is-invalid @enderror">
                                @error('upload')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                {{-- Vista previa del archivo actual (edición) --}}
                                @if ($record_id && !empty($fields['path']))
                                    <div class="mt-2">
                                        @if ($fields['resource_type'] === 'video')
                                            <video controls class="rounded-lg w-full max-h-40">
                                                <source src="{{ asset('storage/' . $fields['path']) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $fields['path']) }}"
                                                alt="Preview"
                                                class="rounded-lg max-h-40 object-cover">
                                        @endif
                                    </div>
                                @endif

                                {{-- Indicador de carga Livewire --}}
                                <div wire:loading wire:target="upload" class="mt-1 text-sm text-blue-500">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Subiendo archivo...
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning" wire:click="store_update"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store_update">Actualizar</span>
                                <span wire:loading wire:target="store_update">
                                    <i class="fas fa-spinner fa-spin"></i> Guardando...
                                </span>
                            </button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store_update"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store_update">Guardar</span>
                                <span wire:loading wire:target="store_update">
                                    <i class="fas fa-spinner fa-spin"></i> Guardando...
                                </span>
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary"
                            onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ============================================================
        |  FIN MODAL
        ============================================================ --}}

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">

                {{-- Cabecera --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Noticias y Multimedia</h1>
                        <p class="text-gray-600">Gestiona noticias, imágenes y videos del sitio</p>
                    </div>
                    <button wire:click="abrirModal"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        Nueva noticia
                    </button>
                </div>

                {{-- Tabla --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                    {{-- Header tabla --}}
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-newspaper"></i>
                            Lista de noticias
                        </h2>
                    </div>

                    {{-- Buscador --}}
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search" type="text"
                            placeholder="Buscar por título, tipo..."
                            class="w-full rounded-none bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>

                    {{-- Tabla responsiva --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-16">
                                        Tipo
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Título
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                        Descripción
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider hidden sm:table-cell w-32">
                                        Fecha
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-28">
                                        Recurso
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-28">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($data as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">

                                        {{-- Tipo --}}
                                        <td class="px-4 py-3">
                                            @php
                                                $badge = match($item->resource_type) {
                                                    'video'   => ['bg-red-100 text-red-700',   'fa-play-circle',  'Video'],
                                                    'image'   => ['bg-green-100 text-green-700','fa-image',        'Imagen'],
                                                    default   => ['bg-blue-100 text-blue-700',  'fa-file-alt',     'Artículo'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge[0] }}">
                                                <i class="fas {{ $badge[1] }}"></i>
                                                {{ $badge[2] }}
                                            </span>
                                        </td>

                                        {{-- Título --}}
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-semibold text-gray-800">{{ $item->title }}</span>
                                        </td>

                                        {{-- Descripción --}}
                                        <td class="px-4 py-3 hidden md:table-cell">
                                            <span class="text-xs text-gray-500 line-clamp-2">
                                                {{ \Str::limit($item->description, 80) }}
                                            </span>
                                        </td>

                                        {{-- Fecha --}}
                                        <td class="px-4 py-3 hidden sm:table-cell">
                                            <span class="text-xs text-gray-600">
                                                {{ $item->date ? formatDate($item->date) : '—' }}
                                            </span>
                                        </td>

                                        {{-- Vista previa recurso --}}
                                        <td class="px-4 py-3 text-center">
                                            @if ($item->path)
                                                @if ($item->resource_type === 'image')
                                                    <img src="{{ asset('storage/' . $item->path) }}"
                                                        alt="{{ $item->title }}"
                                                        class="h-10 w-16 object-cover rounded-lg mx-auto shadow">
                                                @elseif ($item->resource_type === 'video')
                                                    <span class="inline-flex items-center gap-1 text-xs text-red-600 font-semibold">
                                                        <i class="fas fa-film"></i> Video
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">—</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <button wire:click="abrirModal({{ $item->id }})"
                                                    class="p-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors"
                                                    title="Editar">
                                                    <i class="fas fa-pencil text-xs"></i>
                                                </button>
                                                <button onclick="confirmarEliminar({{ $item->id }})"
                                                    class="p-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                                <i class="fas fa-newspaper text-4xl"></i>
                                                <p class="text-sm font-medium">No hay noticias registradas</p>
                                                <button wire:click="abrirModal"
                                                    class="text-indigo-600 hover:underline text-sm">
                                                    Agregar primera noticia
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-500">Mostrar</label>
                            <select wire:model.live="paginate"
                                class="text-xs border border-gray-200 rounded px-2 py-1 focus:outline-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-xs text-gray-500">registros</span>
                        </div>
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
                let el = document.getElementById(modal[0].modal);
                if (el) closeModal(el);
            });

            Livewire.on('abrir-modal', function (modal) {
                let el = document.getElementById(modal[0].modal);
                if (el) openModal(el);
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

            Livewire.on('confirmar-eliminar', e => {
                Swal.fire({
                    title: e[0].title,
                    text: e[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: e[0].confirmButtonText,
                    cancelButtonText: e[0].cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('erase', { id: e[0].id });
                    }
                });
            });
        });

        const confirmarEliminar = (id) => {
            Swal.fire({
                title: 'Eliminar noticia',
                text: '¿Estás seguro de eliminar esta noticia?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('erase', { id });
                }
            });
        };
    </script>
</div>
