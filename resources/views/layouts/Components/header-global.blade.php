{{-- HEADER GLOBAL MINERVA LABS --}}
@php
    // Detecta la sección actual según la URL
    $tituloSeccion = match (true) {
        Request::is('dashboard') => 'MinervaLab',
        Request::is('home') => 'Home',
        Request::is('reservaciones*') => 'Mis Reservaciones',
        Request::is('escena*') => 'Escena / Multimedia',
        default => 'MinervaLab',
    };
@endphp

<div class="sticky top-0 z-50 bg-slate-900 border-b border-slate-800 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

    {{-- Título dinámico --}}
    <h1 class="text-xl md:text-2xl font-bold text-white tracking-wide select-none">
        {{ $tituloSeccion }}
    </h1>

    {{-- Iconos a la derecha --}}
    <div class="flex items-center gap-6" x-data="{ openMenu: false }">

        {{-- Campanita de notificaciones --}}
        <button class="relative text-white hover:text-yellow-400 transition focus:outline-none">
            <i class="fa-solid fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                3
            </span>
        </button>

        {{-- Menú de usuario (icono circular azul) --}}
        <div class="relative">
            <button @click="openMenu = !openMenu" class="flex items-center justify-center focus:outline-none">
                <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                    <i class="fa-solid fa-user text-white"></i>
                </div>
            </button>

            {{-- Menú desplegable --}}
            <div
                x-show="openMenu"
                @click.away="openMenu = false"
                x-transition
                class="absolute right-0 mt-3 w-48 bg-slate-800/95 border border-slate-700 rounded-xl shadow-xl overflow-hidden"
            >
                <a href="{{ route('dashboard') ?? '#' }}" class="block px-4 py-2 text-white hover:bg-slate-700/80">
                    <i class="fa-solid fa-user-circle mr-2 text-blue-400"></i> Perfil
                </a>
                <a href="{{ route('dashboard') ?? '#' }}" class="block px-4 py-2 text-white hover:bg-slate-700/80">
                    <i class="fa-solid fa-gear mr-2 text-cyan-400"></i> Ajustes
                </a>
                <form method="POST" action="{{ route('logout') ?? '#' }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-600/20">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>

{{-- Dependencias (solo se cargan una vez) --}}
@once
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endonce
