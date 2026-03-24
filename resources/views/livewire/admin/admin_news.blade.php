@section('title', 'Administrar noticias')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row min-h-screen">

        {{-- ============================================================
        |  MODAL: Crear / Editar noticia
        ============================================================ --}}
        <div id="modal-news" class="modal" wire:key="modal-{{ $openModal }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $record_id ? 'Editar noticia' : 'Nueva noticia' }}
                        </h5>
                        <button type="button" class="btn-close bg-white/20 hover:bg-white/30 border-0" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>

                    <div class="modal-body">

                        {{-- Tipo de recurso --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Tipo de recurso</label>
                            @if($record_id)
                                {{-- Si está editando, mostrar como texto --}}
                                <div class="bg-white/5 border border-white/20 rounded px-4 py-3 text-white">
                                    <span>
                                        @switch($fields['resource_type'])
                                            @case('article')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-600/20 border border-blue-600/30 text-blue-400">
                                                <i class="fas fa-file-alt"></i>
                                                Artículo
                                            </span>
                                                @break
                                            @case('image')
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-green-600/20 border border-green-600/30 text-green-400">
                                                        <i class="fas fa-image"></i>
                                                        Imagen
                                                    </span>

                                                @break
                                            @case('video')
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-600/20 border border-red-600/30 text-red-400">
                                                        <i class="fas fa-play-circle"></i>
                                                        Video
                                                    </span>
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                                <input type="hidden" wire:model="fields.resource_type">
                            @else
                                {{-- Si está creando, mostrar select --}}
                                <select wire:model.live="fields.resource_type" id="resource_type" class="form-control text-white">
                                    <option value="article">Artículo</option>
                                    <option value="image">Imagen</option>
                                    <option value="video">Video</option>
                                </select>
                            @endif
                            @error('fields.resource_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Título --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Título</label>
                            <input wire:model="fields.title" wire:key="fields.title" type="text" placeholder="Título de la noticia"
                                class="form-control @error('fields.title') is-invalid @enderror">
                            @error('fields.title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="fields.description" wire:key="fields.description" rows="3"
                                placeholder="Descripción breve..."
                                class="form-control @error('fields.description') is-invalid @enderror"></textarea>
                            @error('fields.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Fecha de publicación</label>
                            <input wire:model="fields.date" wire:key="fields.date" type="date"
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
                                    <span class="text-white/40 text-xs">(máx. 50 MB)</span>
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
                                                <source src="{{ asset($fields['path']) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="{{ asset($fields['path']) }}"
                                                alt="Preview"
                                                class="rounded-lg max-h-40 object-cover">
                                        @endif
                                    </div>
                                @endif

                                {{-- Indicador de carga Livewire --}}
                                <div wire:loading wire:target="upload" class="mt-1 text-sm text-blue-500">
                                    <i class="fas fa-spinner fa-spin"></i> Subiendo archivo...
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning bg-yellow-600 hover:bg-yellow-700 text-white border-0" wire:click="store_update"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store_update">Actualizar</span>
                                <span wire:loading wire:target="store_update">
                                    <i class="fas fa-spinner fa-spin"></i> Guardando...
                                </span>
                            </button>
                        @else
                            <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" wire:click="store_update"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store_update">Guardar</span>
                                <span wire:loading wire:target="store_update">
                                    <i class="fas fa-spinner fa-spin"></i> Guardando...
                                </span>
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary bg-white/10 hover:bg-white/20 text-white border border-white/20"
                            onclick="closeModal(this.closest('.modal')); @this.call('cerrarModal', false);">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ============================================================
        |  FIN MODAL
        ============================================================ --}}

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl">

                {{-- Cabecera --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Noticias y Multimedia</h1>
                        <p class="text-white/60">Gestiona noticias, imágenes y videos del sitio</p>
                    </div>
                    <button wire:click="abrirModal"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        Nueva noticia
                    </button>
                </div>

                {{-- Tabla --}}
                <div class="bg-white/5 rounded-2xl border border-white/10 shadow-lg overflow-hidden border border-white/10">

                    {{-- Header tabla --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-newspaper"></i>
                            Lista de noticias
                        </h2>
                    </div>

                    {{-- Buscador --}}
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search" type="text"
                            placeholder="Buscar por título, tipo..."
                            class="w-full rounded-none bg-white/5 text-white placeholder-white/40 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-white/40" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>

                    {{-- Tabla responsiva --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5 border-b-2 border-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white/60 uppercase tracking-wider w-16">
                                        Tipo
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white/60 uppercase tracking-wider">
                                        Título
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white/60 uppercase tracking-wider hidden md:table-cell">
                                        Descripción
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white/60 uppercase tracking-wider hidden sm:table-cell w-32">
                                        Fecha
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-white/60 uppercase tracking-wider w-28">
                                        Recurso
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-white/60 uppercase tracking-wider w-28">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @forelse ($data as $item)
                                    <tr class="hover:bg-white/5 transition-colors">

                                        {{-- Tipo --}}
                                        <td class="px-4 py-3">
                                            @php
                                                $badge = match($item->resource_type) {
                                                    'video'   => ['bg-red-600/20 border border-red-600/30 text-red-400',   'fa-play-circle',  'Video'],
                                                    'image'   => ['bg-green-600/20 border border-green-600/30 text-green-400','fa-image',        'Imagen'],
                                                    default   => ['bg-blue-600/20 border border-blue-600/30 text-blue-400',  'fa-file-alt',     'Artículo'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge[0] }}">
                                                <i class="fas {{ $badge[1] }}"></i>
                                                {{ $badge[2] }}
                                            </span>
                                        </td>

                                        {{-- Título --}}
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-semibold text-white">{{ $item->title }}</span>
                                        </td>

                                        {{-- Descripción --}}
                                        <td class="px-4 py-3 hidden md:table-cell">
                                            <span class="text-xs text-white/50 line-clamp-2">
                                                {{ \Str::limit($item->description, 80) }}
                                            </span>
                                        </td>

                                        {{-- Fecha --}}
                                        <td class="px-4 py-3 hidden sm:table-cell">
                                            <span class="text-xs text-white/60">
                                                {{ $item->date ? formatDate($item->date) : '—' }}
                                            </span>
                                        </td>

                                        {{-- Vista previa recurso --}}
                                        <td class="px-4 py-3 text-center">
                                            @if ($item->path)
                                                @if ($item->resource_type === 'image')
                                                    <img src="{{ asset('storage/' . $item->path) }}"
                                                        alt="{{ $item->title }}"
                                                        class="h-10 w-16 object-cover rounded-lg shadow">
                                                @elseif ($item->resource_type === 'video')
                                                    <span class="inline-flex items-center gap-1 text-xs bg-red-600/20 border border-red-600/30 text-red-400 font-semibold rounded px-2 py-1">
                                                        <i class="fas fa-film"></i> Video
                                                    </span>
                                                @else
                                                    <span class="text-xs text-white/40">—</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-white/40">—</span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <button wire:click="abrirModal({{ $item->id }})"
                                                    class="p-2 bg-blue-600 hover:bg-blue-700 text-white inline-flex items-center justify-center rounded-lg transition-colors"
                                                    title="Editar">
                                                    <i class="fas fa-pencil text-xs"></i>
                                                </button>
                                                <button onclick="confirmarEliminar({{ $item->id }})"
                                                    class="p-2 bg-red-600 hover:bg-red-700 text-white inline-flex items-center justify-center rounded-lg transition-colors"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3 text-white/40">
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
                    <div class="px-6 py-4 border-t border-white/10 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-white/50">Mostrar</label>
                            <select wire:model.live="paginate"
                                class="text-xs border border-white/10 rounded px-2 py-1 focus:outline-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-xs text-white/50">registros</span>
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

            // Abrir modal cuando openModal se establezca en true
            Livewire.watch('openModal', function(value) {
                if (value) {
                    let modal = document.getElementById('modal-news');
                    if (modal) openModal(modal);
                }
            });

            Livewire.on('closeModal', function() {
                Livewire.dispatch('updateOpenModal', false);
            });

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
