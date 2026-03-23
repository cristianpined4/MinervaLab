<div class="relative" x-data="{ openNotifications: false }" wire:poll.8s.visible="loadNotifications">
    <button
        @click="openNotifications = !openNotifications"
        class="relative text-white hover:text-yellow-400 transition focus:outline-none"
        aria-label="Abrir notificaciones"
    >
        <i class="fa-solid fa-bell text-xl"></i>
        @if ($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-white/50 text-white text-xs font-bold min-w-4 h-4 px-1 rounded-full flex items-center justify-center">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="openNotifications"
        @click.away="openNotifications = false"
        x-transition
        class="absolute right-0 mt-3 w-80 bg-slate-800/95 border border-slate-700 rounded-xl shadow-xl overflow-hidden z-50"
    >
        <div class="px-4 py-3 border-b border-slate-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Notificaciones</h3>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" type="button" class="text-xs text-cyan-300 hover:text-cyan-200">
                    Marcar todas leídas
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notification)
                <button
                    wire:click="openNotification({{ $notification->id }})"
                    type="button"
                    class="w-full text-left block px-4 py-3 border-b border-slate-700/50 hover:bg-slate-700/60 transition"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $notification->title ?: 'Notificación' }}</p>
                            <p class="text-xs text-slate-300 mt-1">{{ \Illuminate\Support\Str::limit($notification->description, 90) }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">{{ optional($notification->date)->diffForHumans() }}</p>
                        </div>
                        @if (is_null($notification->read_at))
                            <span class="w-2 h-2 rounded-full bg-cyan-400 mt-1.5 flex-shrink-0"></span>
                        @endif
                    </div>
                </button>
            @empty
                <div class="px-4 py-6 text-sm text-slate-300 text-center">
                    No tienes notificaciones.
                </div>
            @endforelse
        </div>
    </div>
</div>
