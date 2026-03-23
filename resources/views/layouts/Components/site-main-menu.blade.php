@php
    use App\Models\Roles;
    use Illuminate\Support\Facades\Auth;

    $currentUser = Auth::user();
    $role        = $currentUser ? Roles::find($currentUser->id_rol) : null;
    $permissions = $role ? intval($role->permissions) : 99;
    $isAdmin     = $permissions === 1;
    $isDirectivo = $permissions === 2;
    $isTeacher   = $permissions === 3;
@endphp

<div x-data="{ sidebarOpen: false }">

    {{-- Botón hamburguesa / cerrar (solo móvil) --}}
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed bottom-4 right-4 z-50 w-13 h-13 bg-blue-700/80 backdrop-blur rounded-lg flex items-center justify-center text-white shadow-lg"
        style="border-radius: 50%;"
        :aria-label="sidebarOpen ? 'Cerrar menú' : 'Abrir menú'">
        <i class="fas text-sm" :class="sidebarOpen ? 'fa-xmark' : 'fa-bars'"></i>
    </button>

    {{-- Overlay oscuro (solo móvil cuando abierto) --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="lg:hidden fixed inset-0 bg-black/60 z-40"
        style="display:none"></div>

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-slate-900 to-blue-900 text-white shadow-xl flex flex-col z-50 overflow-y-auto
               transition-transform duration-300 ease-in-out
               lg:translate-x-0">

    <!-- Encabezado del menú -->
    <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10 shrink-0">
        <div class="w-9 h-9 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center shadow">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
        </div>
        <h1 class="text-xl font-semibold tracking-tight">Minerva Labs</h1>
        {{-- Botón cerrar (solo móvil, dentro del sidebar) --}}
        <button
            @click="sidebarOpen = false"
            class="lg:hidden absolute top-3 right-3 w-8 h-8 flex items-center justify-center text-white/60 hover:text-white rounded-lg hover:bg-white/10"
            style="margin-top: 8px"
            aria-label="Cerrar menú">
            <i class="fas fa-xmark text-sm"></i>
        </button>
    </div>

    <!-- Navegación principal -->
    <nav class="mt-2 flex-1 space-y-0.5 px-2">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('dashboard') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-chart-pie w-4 text-center {{ request()->routeIs('dashboard') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
            Dashboard
        </a>

        {{-- Home / Noticias --}}
        <a href="{{ route('home') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('home') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-home w-4 text-center {{ request()->routeIs('home') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
            Home
        </a>

        {{-- Mis Reservaciones --}}
        <a href="{{ route('my-reservations') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('my-reservations') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-calendar-check w-4 text-center {{ request()->routeIs('my-reservations') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
            Mis Reservaciones
        </a>

        {{-- Reservar escenas --}}
        <a href="{{ route('reservation') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('reservation') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-vr-cardboard w-4 text-center {{ request()->routeIs('reservation') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
            Reservar escenas
        </a>

        {{-- ─── SECCIÓN ADMIN ─────────────────────────────────────── --}}
        @if ($isAdmin)
            <div class="pt-3 pb-1 px-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-white/30">Administración</span>
            </div>

            {{-- Panel Admin --}}
            <a href="{{ route('admin-dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-dashboard') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-gauge-high w-4 text-center {{ request()->routeIs('admin-dashboard') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Panel Admin
            </a>

            {{-- Reservaciones (admin) --}}
            <a href="{{ route('admin-reservation') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-reservation') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-calendar-days w-4 text-center {{ request()->routeIs('admin-reservation') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Reservaciones
            </a>

            {{-- Usuarios --}}
            <a href="{{ route('admin-user') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-user') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-users w-4 text-center {{ request()->routeIs('admin-user') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Usuarios
            </a>

            {{-- Escenas VR --}}
            <a href="{{ route('admin-scene') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-scene') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-film w-4 text-center {{ request()->routeIs('admin-scene') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Escenas VR
            </a>

            {{-- Salas --}}
            <a href="{{ route('admin-room') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-room') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-door-open w-4 text-center {{ request()->routeIs('admin-room') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Salas
            </a>

            {{-- Mantenimiento Salas --}}
            <a href="{{ route('admin-mantenaince') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-mantenaince') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-screwdriver-wrench w-4 text-center {{ request()->routeIs('admin-mantenaince') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Mantenimiento
            </a>

            {{-- Horarios --}}
            <a href="{{ route('admin-schedule') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-schedule') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-clock w-4 text-center {{ request()->routeIs('admin-schedule') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Horarios
            </a>

            {{-- Noticias --}}
            <a href="{{ route('admin-news') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-news') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-newspaper w-4 text-center {{ request()->routeIs('admin-news') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Noticias
            </a>

            {{-- Reportes --}}
            <a href="{{ route('admin-report') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('admin-report') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-chart-bar w-4 text-center {{ request()->routeIs('admin-report') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Reportes
            </a>
        @endif

        {{-- ─── Asistencia (admin y docentes) ─────────────────────── --}}
        @if ($isAdmin || $isTeacher)
            <a href="{{ route('set-attendance') }}" target="_blank"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('set-attendance') ? 'bg-blue-600/70 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <i class="fas fa-qrcode w-4 text-center {{ request()->routeIs('set-attendance') ? 'text-cyan-300' : 'text-blue-300' }}"></i>
                Control Asistencia
            </a>
        @endif

    </nav>

    <!-- Usuario en la parte inferior -->
    <div class="shrink-0 px-3 py-3 border-t border-white/10 mt-2">
        <div class="flex items-center gap-3 px-2">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center shadow shrink-0">
                <i class="fas fa-user text-white text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">
                    {{ $currentUser?->first_name ?? $currentUser?->username ?? 'Usuario' }}
                    {{ $currentUser?->last_name ?? '' }}
                </p>
                <p class="text-xs text-white/40 truncate">{{ $role?->name ?? 'Sin rol' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-white/50/10 transition-colors">
                <i class="fas fa-right-from-bracket w-4 text-center"></i>
                Cerrar sesión
            </button>
        </form>
    </div>

    </aside>

</div>