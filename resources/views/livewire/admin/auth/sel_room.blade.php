@section('title', 'Seleccionar Sala')

{{-- Ocultar menú / header / footer en login --}}
@section('hide_menu', true)
@section('hide_header', true)
@section('hide_footer', true)

<div class="h-screen w-screen flex flex-col bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative" id="loginForm">

    {{-- SELECCIÓN DE SALA --}}
    @if (!$selectedRoomId)
        <div class="w-full h-full flex flex-col items-center justify-center px-6 py-12">

            {{-- HEADER --}}
            <div class="mb-12 text-center">
                <div class="inline-flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-door-open text-white text-lg"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white">Sistema de Asistencia</h1>
                </div>
                <p class="text-white/60 text-lg">Selecciona una sala para ver las reservaciones</p>
            </div>

            {{-- GRID DE SALAS --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-6xl">
                @foreach ($rooms as $room)
                    <button wire:click="selectRoom({{ $room->id }})"
                        class="group relative overflow-hidden rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        
                        {{-- Gradiente de fondo --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-blue-600/10 group-hover:from-cyan-500/20 group-hover:to-blue-600/20 transition-all"></div>
                        
                        {{-- Borde animado --}}
                        <div class="absolute inset-0 rounded-2xl border border-white/10 group-hover:border-cyan-500/50 transition-all"></div>

                        {{-- Contenido --}}
                        <div class="relative p-8 flex flex-col h-full">
                            {{-- Icono --}}
                            <div class="text-5xl mb-6 text-cyan-400 group-hover:text-cyan-300 transition-colors">
                                <i class="fas fa-qrcode"></i>
                            </div>

                            {{-- Titulo --}}
                            <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-cyan-300 transition-colors">
                                Salón #{{ $room->id }}
                            </h3>

                            {{-- Descripción --}}
                            <p class="text-white/60 text-sm leading-relaxed mb-4">
                                {{ $room->description ?? 'Sala de conferencias' }}
                            </p>

                            {{-- Status Badge --}}
                            <div class="mt-auto">
                                @php
                                    $statusText = $room->status ? 'Disponible' : 'Ocupada';
                                    $statusColor = $room->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400';
                                @endphp
                                <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold {{ $statusColor }} border border-white/10">
                                    <i class="fas {{ $room->status ? 'fa-check-circle' : 'fa-hourglass-end' }} mr-2"></i>
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

        </div>
    @endif

    {{-- VISTA PRINCIPALES: LISTA DE RESERVACIONES Y QR --}}
    @if ($selectedRoomId)
        <div class="w-full h-full flex flex-col md:flex-row gap-0">

            {{-- COLUMNA IZQUIERDA: LISTA DE RESERVACIONES (50%) --}}
            <div class="w-full md:w-1/2 flex flex-col bg-slate-800/50 border-r border-white/5 overflow-hidden">
                
                {{-- HEADER SUPERIOR --}}
                <div class="sticky top-0 z-20 bg-gradient-to-r from-slate-900 to-slate-800 border-b border-white/10 px-6 py-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-door-open text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Salón #{{ $selectedRoomId }}</h2>
                                    <p class="text-white/50 text-sm">{{ now()->locale('es')->format('l, d \d\e F') }}</p>
                                </div>
                            </div>
                        </div>
                        <button wire:click="$set('selectedRoomId', null)" 
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </button>
                    </div>
                    <p class="text-white/60 text-sm">Reservaciones del día</p>
                </div>

                {{-- LISTA DE RESERVACIONES --}}
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3">
                    @forelse ($reservations as $res)
                        @php
                            $startsAt = \Carbon\Carbon::createFromFormat('H:i:s', $res['starts_at']);
                            $endsAt = \Carbon\Carbon::createFromFormat('H:i:s', $res['ends_at']);
                            $now = now();
                            $isActive = $startsAt <= $now && $endsAt >= $now;
                            $isPassed = $endsAt < $now;
                            $minutesUntilEnd = $isActive ? $endsAt->diffInMinutes($now) : null;
                            $isEndingSoon = $isActive && $minutesUntilEnd <= 5 && $minutesUntilEnd >= 0;
                        @endphp

                        <button wire:click="selectReservation({{ $res['id'] }})"
                            class="w-full text-left p-4 rounded-xl transition-all duration-300 group relative overflow-hidden
                                {{ $selectedReservationId === $res['id'] 
                                    ? 'bg-gradient-to-r from-cyan-500/30 to-blue-600/30 border-2 border-cyan-500 ring-2 ring-cyan-400/30 shadow-lg shadow-cyan-500/20' 
                                    : 'bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20' }}
                                {{ $isPassed ? 'opacity-50' : '' }}">

                            {{-- Fondo animado para activa --}}
                            @if ($isActive && !$isPassed)
                                <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-500/5 animate-pulse"></div>
                            @endif

                            <div class="relative flex items-start justify-between">
                                <div class="flex-1">
                                    {{-- Hora y estado --}}
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="text-lg font-bold text-cyan-400">
                                            <i class="fas fa-clock mr-2"></i>{{ $res['starts_at'] }} - {{ $res['ends_at'] }}
                                        </div>
                                        @if ($isActive && !$isPassed)
                                            <span class="px-3 py-1 bg-green-500/30 text-green-300 text-xs font-bold rounded-full border border-green-500/50 animate-pulse">
                                                <i class="fas fa-circle-check mr-1"></i> ACTIVA
                                            </span>
                                        @elseif ($isPassed)
                                            <span class="px-3 py-1 bg-gray-500/30 text-gray-300 text-xs font-bold rounded-full border border-gray-500/50">
                                                <i class="fas fa-circle-xmark mr-1"></i> PASADA
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-500/30 text-slate-300 text-xs font-bold rounded-full border border-slate-500/50">
                                                <i class="fas fa-hourglass-start mr-1"></i> PRÓXIMA
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Usuario --}}
                                    <p class="text-white font-semibold mb-2">
                                        <i class="fas fa-user-circle text-cyan-400 mr-2"></i>{{ $res['user'] }}
                                    </p>

                                    {{-- Cantidad de estudiantes --}}
                                    <p class="text-white/60 text-sm">
                                        <i class="fas fa-users text-cyan-400 mr-2"></i>{{ $res['students'] }} estudiantes
                                    </p>
                                </div>

                                {{-- STATUS BADGE DERECHA --}}
                                <div class="ml-4 flex flex-col gap-2 items-end">
                                    @if ($isActive && !$isPassed && $isEndingSoon)
                                        <span class="px-3 py-2 bg-red-500/30 text-red-300 text-xs font-bold rounded-lg border border-red-500/50 animate-pulse text-right">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $minutesUntilEnd }} MIN
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="flex-1 flex items-center justify-center py-12">
                            <div class="text-center text-white/40">
                                <i class="fas fa-inbox text-6xl mb-4"></i>
                                <p class="text-lg">No hay reservaciones para hoy</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- COLUMNA DERECHA: CÓDIGO QR (50%) --}}
            <div class="w-full md:w-1/2 flex flex-col bg-gradient-to-br from-slate-900 to-slate-800 overflow-hidden">
                
                {{-- CONTENIDO QR --}}
                @if ($selectedReservationId && !empty($qrImage))
                    {{-- Header QR --}}
                    <div class="flex-shrink-0 bg-gradient-to-r from-slate-900 to-slate-800 border-b border-white/10 px-6 py-6 text-center">
                        <h3 class="text-2xl font-bold text-white mb-2">
                            <i class="fas fa-qrcode text-cyan-400 mr-2"></i> Código QR Activo
                        </h3>
                        <p class="text-white/60 text-sm">Captura el código para marcar asistencia</p>
                    </div>

                    {{-- QR Image Container --}}
                    <div class="flex-1 flex items-center justify-center p-6">
                        <div class="bg-gradient-to-br from-white to-gray-100 p-8 rounded-2xl shadow-2xl transform transition-all hover:scale-105 duration-300">
                            <img src="{{ $qrImage }}" class="w-80 h-80 object-contain" alt="QR Code">
                        </div>
                    </div>

                    {{-- Footer info --}}
                    <div class="flex-shrink-0 bg-gradient-to-t from-slate-900 to-transparent px-6 py-6 border-t border-white/10">
                        <div class="text-center text-white/60 text-sm">
                            <p><i class="fas fa-info-circle mr-2"></i> Código se actualiza automáticamente</p>
                        </div>
                    </div>
                @else
                    {{-- Sin Reservación Seleccionada --}}
                    <div class="flex-1 flex flex-col items-center justify-center text-center px-6 py-12">
                        <div class="text-white/30 mb-6">
                            <i class="fas fa-qrcode text-8xl mb-6 block"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Ninguna reservación</h3>
                        <p class="text-white/60 text-lg">Selecciona una reservación activa para generar el código QR</p>
                        <div class="mt-8 p-4 rounded-lg bg-white/5 border border-white/10 text-white/70 text-sm">
                            <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                            La reservación debe estar en progreso
                        </div>
                    </div>
                @endif

            </div>

        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:initialized', function () {

            // Actualizar cada 5 segundos para mejor reactividad
            setInterval(() => {
                Livewire.dispatch('refreshReservations');
            }, 5000);

            // Notificación cuando falta 5 minutos
            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: e[0].icon ?? 'warning',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });

        });
    </script>
</div>


