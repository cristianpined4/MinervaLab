<!-- Sidebar principal -->
<aside
    class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-slate-900 to-blue-900 text-white shadow-xl flex flex-col justify-between z-50">

    <!-- Encabezado del menú -->
    <div>
        <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
            <div class="w-9 h-9 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <h1 class="text-xl font-semibold">Minerva Labs</h1>
        </div>

        <!-- Opciones del menú -->
        <nav class="mt-4 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-5 py-2.5 text-sm font-medium hover:bg-blue-700/40 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700/60' : '' }}">
                <i class="fas fa-chart-pie mr-3 text-blue-300"></i> MinervaLab
            </a>

            <a href="{{ route('home') }}"
                class="flex items-center px-5 py-2.5 text-sm font-medium hover:bg-blue-700/40 transition {{ request()->is('/') ? 'bg-blue-700/60' : '' }}">
                <i class="fas fa-home mr-3 text-blue-300"></i> Home
            </a>

            <a href="{{ route('reservaciones') }}"
                class="flex items-center px-5 py-2.5 text-sm font-medium hover:bg-blue-700/40 transition {{ request()->routeIs('reservaciones') ? 'bg-blue-700/60' : '' }}">
                <i class="fas fa-calendar-check mr-3 text-blue-300"></i> Mis Reservaciones
            </a>

            <a href="{{ route('escena') }}"
                class="flex items-center px-5 py-2.5 text-sm font-medium hover:bg-blue-700/40 transition {{ request()->routeIs('multimedia') ? 'bg-blue-700/60' : '' }}">
                <i class="fas fa-photo-video mr-3 text-blue-300"></i> Escena / Multimedia
            </a>
        </nav>
    </div>
</aside>
