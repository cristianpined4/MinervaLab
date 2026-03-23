@section('title', 'Dashboard')
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
                            <i class="fas fa-vr-cardboard"></i> Minerva Labs · Dashboard
                        </p>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                            ¡Hola, {{ auth()->user()?->first_name ?? auth()->user()?->name ?? 'Usuario' }}!
                        </h1>
                        <p class="text-blue-200/70 mb-3">
                            Bienvenido al Sistema de Gestión de Realidad Virtual
                        </p>
                        <div class="flex items-center gap-4 text-sm text-blue-300/60">
                            <span>
                                <i class="fas fa-calendar"></i>
                                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ now()->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('reservation') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-plus-circle"></i> Nueva reservación
                        </a>
                        <a href="{{ route('my-reservations') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold rounded-xl transition-all duration-300">
                            <i class="fas fa-list-check"></i> Mis reservaciones
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl px-4 md:px-6 py-8 space-y-8">

            {{-- ============================================================
            |  ESTADÍSTICAS REALES
            ============================================================ --}}
            <section>
                <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-cyan-400"></i>
                    Resumen de mis reservaciones
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Total --}}
                    <div class="bg-white/5 border border-white/10 hover:border-blue-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200/60 text-xs font-medium mb-1 uppercase tracking-wider">Total</p>
                                <p class="text-3xl font-bold text-white">{{ $totalReservations }}</p>
                            </div>
                            <div class="w-11 h-11 bg-white/50/20 border border-blue-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar text-blue-400"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Aprobadas --}}
                    <div class="bg-white/5 border border-white/10 hover:border-emerald-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200/60 text-xs font-medium mb-1 uppercase tracking-wider">Aprobadas</p>
                                <p class="text-3xl font-bold text-emerald-400">{{ $approvedReservations }}</p>
                            </div>
                            <div class="w-11 h-11 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-check-circle text-emerald-400"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Pendientes --}}
                    <div class="bg-white/5 border border-white/10 hover:border-yellow-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200/60 text-xs font-medium mb-1 uppercase tracking-wider">Pendientes</p>
                                <p class="text-3xl font-bold text-yellow-400">{{ $pendingReservations }}</p>
                            </div>
                            <div class="w-11 h-11 bg-white/50/20 border border-yellow-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-hourglass-half text-yellow-400"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Rechazadas --}}
                    <div class="bg-white/5 border border-white/10 hover:border-red-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200/60 text-xs font-medium mb-1 uppercase tracking-wider">Rechazadas</p>
                                <p class="text-3xl font-bold text-red-400">{{ $declinedReservations }}</p>
                            </div>
                            <div class="w-11 h-11 bg-white/50/20 border border-red-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-times-circle text-red-400"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {{-- ============================================================
            |  PRÓXIMAS SESIONES APROBADAS
            ============================================================ --}}
            @if ($upcoming->isNotEmpty())
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-calendar-check text-cyan-400"></i>
                        Próximas sesiones aprobadas
                    </h2>
                    <a href="{{ route('my-reservations') }}"
                        class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                        Ver todas <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($upcoming as $res)
                        <div class="bg-white/5 border border-white/10 hover:border-cyan-500/40 rounded-2xl p-5 backdrop-blur-sm transition-all duration-300 group">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center">
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
                                <i class="fas fa-users text-xs"></i> {{ $res->students }} estudiante(s)
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- ============================================================
            |  HISTORIAL RECIENTE
            ============================================================ --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-history text-blue-400"></i>
                        Historial reciente
                    </h2>
                    <a href="{{ route('my-reservations') }}"
                        class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                        Ver todo <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                @if ($recent->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center bg-white/5 border border-white/10 rounded-2xl">
                        <i class="fas fa-calendar-xmark text-3xl text-blue-400/40 mb-3"></i>
                        <p class="text-white/50 font-medium">Sin reservaciones aún</p>
                        <a href="{{ route('reservation') }}" class="mt-3 text-sm text-cyan-400 hover:underline">
                            Crear primera reservación
                        </a>
                    </div>
                @else
                    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-white/5 border-b border-white/10">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-300/70 uppercase tracking-wider">Sala</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-300/70 uppercase tracking-wider">Fecha</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-300/70 uppercase tracking-wider hidden sm:table-cell">Horario</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-300/70 uppercase tracking-wider hidden md:table-cell">Estudiantes</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-300/70 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($recent as $res)
                                        @php
                                            $statusMap = [
                                                0 => ['label' => 'Pendiente',  'class' => 'bg-white/50/20 text-yellow-400 border-yellow-500/30'],
                                                1 => ['label' => 'Aprobada',   'class' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'],
                                                2 => ['label' => 'Rechazada',  'class' => 'bg-white/50/20 text-red-400 border-red-500/30'],
                                                3 => ['label' => 'Cancelada',  'class' => 'bg-white/50/20 text-white/40 border-gray-500/30'],
                                                4 => ['label' => 'No asistió', 'class' => 'bg-orange-500/20 text-orange-400 border-orange-500/30'],
                                            ];
                                            $status = $statusMap[$res->status] ?? $statusMap[0];
                                        @endphp
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="px-4 py-3 text-white font-medium">
                                                {{ $res->HasRoom?->description ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-blue-200/70">
                                                {{ $res->date ? \Carbon\Carbon::parse($res->date)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-blue-200/70 hidden sm:table-cell">
                                                {{ substr($res->starts_at, 0, 5) }} – {{ substr($res->ends_at, 0, 5) }}
                                            </td>
                                            <td class="px-4 py-3 text-blue-200/70 hidden md:table-cell">
                                                {{ $res->students }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $status['class'] }}">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>

        </div>
    </main>

    @include('layouts.components.footer-global')
</div>
