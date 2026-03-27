@section('title', 'Inicio')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="relative z-10 min-h-screen bg-white/5">

        {{-- ============================================================
        |  BIENVENIDA
        ============================================================ --}}
        <section class="relative overflow-hidden border-b border-white/10 bg-white/5 backdrop-blur-sm">
            <div class="max-w-7xl px-4 md:px-6 py-10 md:py-14">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest mb-2">
                            <i class="fas fa-vr-cardboard"></i> Minerva Labs
                        </p>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                            ¡Hola, {{ auth()->user()?->first_name ?? 'Usuario' }}!
                        </h1>
                        <p class="text-blue-200/70 mb-3">
                            Bienvenido al portal de realidad virtual MinervaLab
                        </p>
                        <div class="flex items-center gap-4 text-sm text-blue-300/60">
                            <span>
                                <i class="fas fa-calendar"></i>
                                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('reservation') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-plus-circle"></i>
                            Nueva reservación
                        </a>
                        <a href="{{ route('my-reservations') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold rounded-xl transition-all duration-300">
                            <i class="fas fa-list-check"></i>
                            Mis reservaciones
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl px-4 md:px-6 py-8 space-y-10">

            {{-- ============================================================
            |  PRÓXIMAS RESERVACIONES APROBADAS (usuario actual)
            ============================================================ --}}
            @if ($upcomingReservations->isNotEmpty())
                <section>
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-calendar-check text-cyan-400"></i>
                            Mis próximas sesiones
                        </h2>
                        <a href="{{ route('my-reservations') }}"
                            class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                            Ver todas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($upcomingReservations as $res)
                            <div class="bg-white/5 border border-white/10 hover:border-cyan-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-vr-cardboard text-white text-sm"></i>
                                    </div>
                                    <span class="px-2 py-0.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 text-xs font-semibold rounded-full">
                                        Aprobada
                                    </span>
                                </div>
                                <p class="text-white font-semibold mb-1">
                                    {{ $res->HasRoom?->description ?? 'Sala' }}
                                </p>
                                <p class="text-blue-200/60 text-sm">
                                    <i class="fas fa-calendar text-xs"></i>
                                    {{ formatDate($res->date) }}
                                    &nbsp;·&nbsp;
                                    <i class="fas fa-clock text-xs"></i>
                                    {{ formatTime($res->starts_at) }} – {{ formatTime($res->ends_at) }}
                                </p>
                                <p class="text-blue-200/50 text-xs mt-1">
                                    <i class="fas fa-users text-xs"></i>
                                    {{ $res->students }} estudiante(s)
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ============================================================
            |  NOTICIAS Y MULTIMEDIA
            ============================================================ --}}
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-newspaper text-blue-400"></i>
                        Noticias y Multimedia
                    </h2>
                </div>

                @if ($news->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-4">
                            <i class="fas fa-newspaper text-2xl text-blue-400/50"></i>
                        </div>
                        <p class="text-white/50 font-medium">Sin noticias por el momento</p>
                        <p class="text-white/30 text-sm mt-1">El administrador publicará contenido próximamente</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($news as $item)
                            <article
                                role="button"
                                tabindex="0"
                                onclick="openNewsPreview(this)"
                                onkeydown="if(event.key==='Enter'||event.key===' '){ event.preventDefault(); openNewsPreview(this); }"
                                data-type="{{ $item->resource_type }}"
                                data-title="{{ $item->title }}"
                                data-description="{{ $item->description ?? '' }}"
                                data-date="{{ $item->date ? formatDate($item->date) : '' }}"
                                data-image-url="{{ $item->resource_type === 'image' && $item->path ? asset('news/images/' . $item->path) : '' }}"
                                data-video-url="{{ $item->resource_type === 'video' && $item->path ? asset('news/videos/' . $item->path) : '' }}"
                                class="bg-white/5 border border-white/10 hover:border-blue-500/40 focus:outline-none focus:ring-2 focus:ring-cyan-400/70 rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-300 group flex flex-col cursor-pointer"
                            >

                                {{-- Miniatura --}}
                                <div class="relative overflow-hidden h-44 bg-gradient-to-br
                                    @if($item->resource_type === 'video') from-red-900/40 to-red-950/60
                                    @elseif($item->resource_type === 'image') from-blue-900/40 to-blue-950/60
                                    @else from-indigo-900/40 to-indigo-950/60 @endif">

                                    @if ($item->resource_type === 'image' && $item->path)
                                        <img src="{{ asset('news/images/' . $item->path) }}"
                                            alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                    @elseif ($item->resource_type === 'video' && $item->path)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/20 transition-colors">
                                            <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                                <i class="fas fa-play text-white"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            @if ($item->resource_type === 'video')
                                                <i class="fas fa-play-circle text-red-400/60 text-5xl"></i>
                                            @elseif ($item->resource_type === 'image')
                                                <i class="fas fa-image text-blue-400/60 text-5xl"></i>
                                            @else
                                                <i class="fas fa-file-alt text-indigo-400/60 text-5xl"></i>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Badge tipo --}}
                                    <div class="absolute top-3 left-3">
                                        @php
                                            $badge = match($item->resource_type) {
                                                'video'  => ['bg-white/50/80',    'fa-play',    'Video'],
                                                'image'  => ['bg-white/50/80',   'fa-image',   'Imagen'],
                                                default  => ['bg-indigo-500/80', 'fa-file-alt','Artículo'],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 {{ $badge[0] }} backdrop-blur-sm text-white text-xs font-semibold rounded-full border border-white/20">
                                            <i class="fas {{ $badge[1] }} text-[10px]"></i> {{ $badge[2] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Contenido textual --}}
                                <div class="p-5 flex flex-col flex-1">
                                    <p class="text-blue-300/60 text-xs mb-2">
                                        <i class="fas fa-calendar text-[10px]"></i>
                                        {{ $item->date ? formatDate($item->date) : '' }}
                                    </p>
                                    <h3 class="text-white font-bold text-base mb-2 leading-snug group-hover:text-cyan-300 transition-colors">
                                        {{ $item->title }}
                                    </h3>
                                    @if ($item->description)
                                        <p class="text-blue-200/60 text-sm leading-relaxed flex-1">
                                            {{ \Str::limit($item->description, 110) }}
                                        </p>
                                    @endif

                                    <span class="mt-4 inline-flex items-center gap-2 self-start text-cyan-300 text-xs font-semibold">
                                        <i class="fas fa-eye"></i>
                                        Click para previsualizar
                                    </span>
                                </div>

                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </main>

    <div id="modal-news-preview" class="modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl" style="background-color: var(--color-slate-900);">
            <div class="modal-content bg-slate-900 text-white">
                <div class="modal-header border-b border-white/10">
                    <div>
                        <h2 id="news_preview_title" class="text-xl font-bold">Previsualización</h2>
                        <p id="news_preview_date" class="text-xs text-blue-200/60 mt-1"></p>
                    </div>
                    <button type="button" class="btn btn-close text-white text-3xl" aria-label="Cerrar"
                        onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>

                <div class="modal-body p-0">
                    <div id="news_preview_media_wrap" class="w-full h-[280px] md:h-[420px] bg-black/40 flex items-center justify-center">
                        <img id="news_preview_image" src="" alt="Previsualización"
                            class="hidden w-full h-full object-contain">

                        <video id="news_preview_video" class="hidden w-full h-full" controls>
                            <source id="news_preview_video_source" src="" type="video/mp4">
                            Tu navegador no soporta reproducción de video.
                        </video>

                        <div id="news_preview_article" class="hidden text-center px-6">
                            <i class="fas fa-file-alt text-indigo-400/70 text-6xl mb-3"></i>
                            <p class="text-white/70 text-sm">Este contenido es de tipo artículo.</p>
                        </div>
                    </div>

                    <div class="p-5 md:p-6">
                        <p id="news_preview_desc" class="text-blue-100/80 text-sm md:text-base leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.components.footer-global')

    <script>
        const openNewsPreview = (trigger) => {
            const modal = document.getElementById('modal-news-preview');
            const title = document.getElementById('news_preview_title');
            const date = document.getElementById('news_preview_date');
            const desc = document.getElementById('news_preview_desc');

            const image = document.getElementById('news_preview_image');
            const video = document.getElementById('news_preview_video');
            const videoSource = document.getElementById('news_preview_video_source');
            const article = document.getElementById('news_preview_article');

            const type = trigger.dataset.type || 'article';
            const itemTitle = trigger.dataset.title || 'Previsualización';
            const itemDate = trigger.dataset.date || '';
            const itemDesc = trigger.dataset.description || 'Sin descripción.';
            const imageUrl = trigger.dataset.imageUrl || '';
            const videoUrl = trigger.dataset.videoUrl || '';

            title.textContent = itemTitle;
            date.textContent = itemDate;
            desc.textContent = itemDesc;

            image.classList.add('hidden');
            video.classList.add('hidden');
            article.classList.add('hidden');

            video.pause();
            videoSource.src = '';
            video.load();

            if (type === 'image' && imageUrl) {
                image.src = imageUrl;
                image.classList.remove('hidden');
            } else if (type === 'video' && videoUrl) {
                videoSource.src = videoUrl;
                video.load();
                video.classList.remove('hidden');
            } else {
                article.classList.remove('hidden');
            }

            openModal(modal);
        };

        window.openNewsPreview = openNewsPreview;
    </script>
</div>
