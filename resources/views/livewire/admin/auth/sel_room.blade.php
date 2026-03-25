@section('title', 'Seleccionar Sala')

{{-- Ocultar menú / header / footer en login --}}
@section('hide_menu', true)
@section('hide_header', true)
@section('hide_footer', true)


<div class="h-screen w-screen flex flex-col bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 relative overflow-hidden" id="loginForm">
    {{-- Elementos de audio para sonidos --}}
    <audio id="activationSound" preload="auto">
        <source src="data:audio/wav;base64,UklGRiYAAABXQVZFZm10IBAAAAABAAEAQB8AAAB9AAACABAAZGF0YQIAAAAAAA==" type="audio/wav">
    </audio>
    <audio id="endingSound" preload="auto">
        <source src="data:audio/wav;base64,UklGRiYAAABXQVZFZm10IBAAAAABAAEAQB8AAAB9AAACABAAZGF0YQIAAAAAAA==" type="audio/wav">
    </audio>
    {{-- Fondo decorativo --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
    </div>

    {{-- SELECCIÓN DE SALA --}}
    @if (!$selectedRoomId)
        <div class="w-full h-full flex flex-col items-center justify-center px-4 sm:px-6 py-12 relative z-10">

            {{-- HEADER --}}
            <div class="mb-16 text-center">
                <div class="inline-flex items-center justify-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-purple-600 flex items-center justify-center shadow-2xl shadow-cyan-500/50 transform hover:scale-110 transition-transform">
                        <i class="fas fa-door-open text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-5xl sm:text-6xl font-bold text-white mb-3 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Sistema de Asistencia
                </h1>
                <p class="text-white/70 text-lg">Selecciona una sala para gestionar reservaciones</p>
            </div>

            {{-- GRID DE SALAS --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-6xl">
                @foreach ($rooms as $room)
                    <button wire:click="selectRoom({{ $room->id }})"
                        class="group relative overflow-hidden rounded-2xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        
                        {{-- Gradiente de fondo --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/15 to-blue-600/15 group-hover:from-cyan-500/25 group-hover:to-blue-600/25 transition-all"></div>
                        
                        {{-- Borde animado --}}
                        <div class="absolute inset-0 rounded-2xl border-2 border-white/10 group-hover:border-cyan-500/70 transition-all shadow-lg group-hover:shadow-cyan-500/20"></div>

                        {{-- Contenido --}}
                        <div class="relative p-8 flex flex-col h-full min-h-64">
                            {{-- Icono --}}
                            <div class="text-6xl mb-6 text-cyan-400 group-hover:text-cyan-300 transition-colors transform group-hover:scale-110">
                                <i class="fas fa-door-open"></i>
                            </div>

                            {{-- Titulo --}}
                            <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-cyan-300 transition-colors">
                                Salón #{{ $room->id }}
                            </h3>

                            {{-- Descripción --}}
                            <p class="text-white/60 text-sm leading-relaxed mb-6 flex-grow">
                                {{ $room->description ?? 'Sala de conferencias' }}
                            </p>

                            {{-- Status Badge --}}
                            <div class="mt-auto">
                                @php
                                    $statusText = $room->status ? 'Disponible' : 'Ocupada';
                                    $statusColor = $room->status 
                                        ? 'bg-gradient-to-r from-green-500/30 to-emerald-500/30 text-green-300 border-green-500/50' 
                                        : 'bg-gradient-to-r from-red-500/30 to-orange-500/30 text-red-300 border-red-500/50';
                                @endphp
                                <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold {{ $statusColor }} border transition-all">
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
        <div class="w-full h-full flex flex-col md:flex-row gap-0 relative z-10">

            {{-- COLUMNA IZQUIERDA: LISTA DE RESERVACIONES (50%) --}}
            <div class="w-full md:w-1/2 flex flex-col bg-gradient-to-b from-slate-800/80 to-slate-900/80 backdrop-blur-sm border-r border-white/5 overflow-hidden">
                
                {{-- HEADER SUPERIOR --}}
                <div class="sticky top-0 z-20 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border-b-2 border-cyan-500/30 px-6 py-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/30">
                                    <i class="fas fa-door-open text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Salón #{{ $selectedRoomId }}</h2>
                                    <p class="text-cyan-400/80 text-sm font-medium">{{ \Carbon\Carbon::now()->locale('es')->translatedFormat('l, d \d\e F') }}</p>
                                </div>
                            </div>
                        </div>
                        <button wire:click="$set('selectedRoomId', null)" 
                            class="px-4 py-2 bg-gradient-to-r from-white/10 to-white/5 hover:from-white/20 hover:to-white/10 text-white rounded-lg transition-all duration-300 font-semibold border border-white/10 hover:border-white/30">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </button>
                    </div>
                    <p class="text-white/60 text-sm ml-16">Reservaciones de hoy</p>
                </div>

                {{-- LISTA DE RESERVACIONES --}}
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3 scrollbar-thin scrollbar-thumb-cyan-500/30 scrollbar-track-transparent">
                    @forelse ($reservations as $res)
                        @php
                            // Comparar solo horas (H:i:s format)
                            $currentTime = now()->format('H:i:s');
                            $isActive = $res['starts_at'] <= $currentTime && $res['ends_at'] >= $currentTime;
                            $isPassed = $res['ends_at'] < $currentTime;
                            
                            // Para mostrar bonito
                            $startsAt = \Carbon\Carbon::createFromFormat('H:i:s', $res['starts_at']);
                            $endsAt = \Carbon\Carbon::createFromFormat('H:i:s', $res['ends_at']);
                            $startsAtFormatted = $startsAt->format('h:i A');
                            $endsAtFormatted = $endsAt->format('h:i A');
                            
                            // Minutos hasta fin
                            $minutesUntilEnd = null;
                            if ($isActive) {
                                $minutesUntilEnd = $endsAt->diffInMinutes($startsAt->setTimeFromTimeString($currentTime));
                            }
                            $isEndingSoon = $isActive && $minutesUntilEnd !== null && $minutesUntilEnd <= 5 && $minutesUntilEnd >= 0;
                        @endphp

                        <button wire:click="selectReservation({{ $res['id'] }})"
                            class="w-full text-left transition-all duration-300 group relative overflow-hidden
                                {{ $selectedReservationId === $res['id'] 
                                    ? 'ring-2 ring-cyan-400/60 shadow-xl shadow-cyan-500/40' 
                                    : '' }}"
                            wire:key="res-{{ $res['id'] }}"
                            {{ $isPassed ? 'disabled' : '' }}>

                            {{-- Card container con gradientes --}}
                            <div class="p-5 rounded-2xl transition-all duration-300 border-2
                                {{ $selectedReservationId === $res['id'] 
                                    ? 'bg-gradient-to-r from-cyan-500/50 to-blue-600/50 border-cyan-400' 
                                    : 'bg-gradient-to-r from-white/7 to-white/3 border-white/10 hover:from-white/12 hover:to-white/7 hover:border-cyan-400/50' }}
                                {{ $isActive ? 'relative' : '' }}"
                                style="
                                @if ($isPassed)
                                    opacity: 0.5;
                                @endif
                                ">

                                {{-- Animación de pulso para activa --}}
                                @if ($isActive && !$isPassed)
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-green-400/10 to-emerald-400/10 animate-pulse"></div>
                                @endif

                                <div class="relative space-y-3">
                                    {{-- Primera fila: Hora y Estado --}}
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-cyan-500/30 flex items-center justify-center border border-cyan-500/50">
                                                <i class="fas fa-clock text-cyan-300 text-xs"></i>
                                            </div>
                                            <span class="text-white font-bold text-sm">{{ $startsAtFormatted }} - {{ $endsAtFormatted }}</span>
                                        </div>
                                        @if ($isActive && !$isPassed)
                                            <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-500/50 to-emerald-500/50 text-green-100 text-xs font-bold rounded-full border border-green-500/70 animate-pulse shadow-lg shadow-green-500/30">
                                                <i class="fas fa-dot-circle text-green-300 mr-1.5 text-xs"></i> EN CURSO
                                            </span>
                                        @elseif ($isPassed)
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-500/20 text-gray-300 text-xs font-bold rounded-full border border-gray-500/30">
                                                <i class="fas fa-circle-xmark mr-1.5"></i> FINALIZADA
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-blue-500/20 text-blue-300 text-xs font-bold rounded-full border border-blue-500/30">
                                                <i class="fas fa-hourglass-start text-xs mr-1.5"></i> PRÓXIMA
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Usuario destacado --}}
                                    <div class="bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-xl p-4 border border-cyan-500/40">
                                        <div class="text-cyan-300 text-xs uppercase tracking-widest font-semibold mb-1.5">{{ $res['rol_user'] ?? 'Sin Rol' }}</div>
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-cyan-500/30">
                                                {{ substr($res['user'], 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-white font-bold text-lg leading-tight truncate">
                                                    {{ $res['user'] }}
                                                </p>
                                                <p class="text-cyan-300/80 text-xs mt-0.5 font-medium">
                                                    <i class="fas fa-at mr-1 text-cyan-400/60"></i>{{ $res['username'] ?? 'sin_usuario' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Información adicional --}}
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center text-white/70">
                                            <i class="fas fa-users text-blue-400 mr-2"></i>
                                            <span>{{ $res['students'] }} {{ $res['students'] == 1 ? 'estudiante' : 'estudiantes' }}</span>
                                        </div>
                                    </div>

                                    {{-- Advertencia si falta poco tiempo --}}
                                    @if ($isActive && !$isPassed && $isEndingSoon)
                                        <div class="bg-gradient-to-r from-red-500/40 to-orange-500/40 border-l-4 border-red-500 rounded-lg p-3 flex items-center gap-2">
                                            <i class="fas fa-exclamation-triangle text-red-300 text-lg flex-shrink-0"></i>
                                            <div>
                                                <div class="text-red-200 text-xs font-bold">¡PRÓXIMO A FINALIZAR!</div>
                                                <div class="text-red-100/80 text-xs">Finaliza en {{ $minutesUntilEnd }} minuto{{ $minutesUntilEnd != 1 ? 's' : '' }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="flex-1 flex items-center justify-center py-12">
                            <div class="text-center text-white/40">
                                <i class="fas fa-inbox text-6xl mb-4 opacity-40"></i>
                                <p class="text-lg font-medium">No hay reservaciones para hoy</p>
                                <p class="text-sm mt-2">Las salas aparecerán aquí cuando se registren</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- COLUMNA DERECHA: CÓDIGO QR (50%) --}}
            <div class="w-full md:w-1/2 flex flex-col bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 overflow-hidden relative" wire:key="qr-section">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute top-1/2 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl transform translate-x-1/2"></div>
                </div>
                
                {{-- CONTENIDO QR --}}
                @if ($selectedReservationId && $showQR && !empty($qrImage))
                    {{-- Header QR --}}
                    <div class="flex-shrink-0 bg-gradient-to-r from-slate-950 to-slate-900 border-b-2 border-cyan-500/30 px-6 py-6 text-center relative z-10" wire:transition>
                        <h3 class="text-2xl font-bold text-white mb-2">
                            <i class="fas fa-qrcode text-cyan-400 mr-2 animate-pulse"></i> Código QR Activo
                        </h3>
                        <p class="text-white/60 text-sm">Escanea para registrar asistencia</p>
                    </div>

                    {{-- QR Image Container --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-6 relative z-10" wire:transition>
                        <div class="bg-white p-8 rounded-3xl shadow-2xl shadow-cyan-500/30 transform transition-all hover:scale-105 duration-300 border-2 border-white/20">
                            <img src="{{ $qrImage }}" class="w-72 h-72 object-contain" alt="QR Code" wire:key="qr-image-{{ $selectedReservationId }}" loading="lazy">
                        </div>
                    </div>

                    {{-- Footer info --}}
                    <div class="flex-shrink-0 bg-gradient-to-t from-slate-950 via-slate-900/50 to-transparent px-6 py-6 border-t border-white/5 relative z-10" wire:transition>
                        <div class="text-center">
                            <div class="inline-block px-4 py-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-sm">
                                <i class="fas fa-sync-alt animate-spin mr-2"></i> Código actualizado automáticamente
                            </div>
                        </div>
                    </div>
                @elseif ($selectedReservationId && !$showQR)
                    {{-- Cargando QR --}}
                    <div class="flex-1 flex flex-col items-center justify-center text-center px-6 py-12 relative z-10" wire:transition>
                        <div class="mb-6">
                            <i class="fas fa-qrcode text-7xl text-cyan-400/40 animate-bounce"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Generando Código QR</h3>
                        <p class="text-white/60 text-lg">Preparando código para la reservación seleccionada</p>
                        <div class="mt-8 w-full max-w-xs">
                            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full animate-pulse" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Sin Reservación Seleccionada --}}
                    <div class="flex-1 flex flex-col items-center justify-center text-center px-6 py-12 relative z-10" wire:transition>
                        <div class="mb-8">
                            <i class="fas fa-qrcode text-8xl text-white/10 mb-6 block"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Selecciona una Reservación</h3>
                        <p class="text-white/60 text-lg mb-6">El código QR aparecerá aquí automáticamente</p>
                        <div class="p-6 rounded-xl bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border-2 border-cyan-500/30 text-white/70 text-sm max-w-xs">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                            <span class="font-semibold text-white">Tip:</span> La reservación debe estar activa para generar el código
                        </div>
                    </div>
                @endif

            </div>

        </div>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función para generar sonido (beep)
        function playBeep(frequency = 800, duration = 200, volume = 0.3) {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = frequency;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(volume, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration / 1000);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + duration / 1000);
        }

        document.addEventListener('livewire:initialized', function () {

            // Actualizar cada segundo para capturar cambios de minuto
            setInterval(() => {
                Livewire.dispatch('refreshReservations');
            }, 1000);

            // Observer para sonidos de activación (más largos)
            Livewire.on('playActivationSound', () => {
                playBeep(800, 500, 0.4); // Beep ascendente más largo
                setTimeout(() => playBeep(1000, 400, 0.35), 200);
            });

            // Observer para sonidos de finalización (más largos)
            Livewire.on('playEndingSound', () => {
                playBeep(600, 400, 0.35);
                setTimeout(() => playBeep(600, 400, 0.35), 350);
                setTimeout(() => playBeep(600, 400, 0.35), 700);
            });

            // Observer para sonidos de cuenta regresiva (cada minuto)
            Livewire.on('playCountdownSound', () => {
                playBeep(1200, 500, 0.5); // Beep más agudo y audible
                setTimeout(() => playBeep(1200, 300, 0.4), 600);
            });

            // Notificación con toast
            Livewire.on('swal:notify', (payload) => {
                // Livewire 3 con parámetros nombrados envía como objeto
                const { icon = 'warning', title = '', message = '' } = payload;
                
                if (message && message.trim()) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: String(title),
                        html: String(message),
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                }
            });

        });
    </script>
</div>


