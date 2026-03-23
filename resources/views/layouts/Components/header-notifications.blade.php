<div
    class="relative"
    x-data="headerNotificationsWidget({
        feedUrl: '{{ route('notifications.feed') }}',
        readAllUrl: '{{ route('notifications.read-all-json') }}',
        openBaseUrl: '{{ url('notifications') }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    x-init="init()"
>
    <button
        @click="toggle()"
        class="relative text-white hover:text-yellow-400 transition focus:outline-none"
        aria-label="Abrir notificaciones"
    >
        <i class="fa-solid fa-bell text-xl"></i>
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 bg-white/50 text-white text-xs font-bold min-w-4 h-4 px-1 rounded-full flex items-center justify-center" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </template>
    </button>

    <div
        x-show="openNotifications"
        @click.away="openNotifications = false"
        x-transition
        class="absolute right-0 mt-3 w-80 bg-slate-800/95 border border-slate-700 rounded-xl shadow-xl overflow-hidden z-50"
    >
        <div class="px-4 py-3 border-b border-slate-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Notificaciones</h3>
            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()" type="button" class="text-xs text-cyan-300 hover:text-cyan-200">Marcar todas leídas</button>
            </template>
        </div>

        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-6 text-sm text-slate-300 text-center">No tienes notificaciones.</div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <button
                    @click="openNotification(notification.id)"
                    type="button"
                    class="w-full text-left block px-4 py-3 border-b border-slate-700/50 hover:bg-slate-700/60 transition"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate" x-text="notification.title || 'Notificación'"></p>
                            <p class="text-xs text-slate-300 mt-1" x-text="notification.description || ''"></p>
                            <p class="text-[11px] text-slate-400 mt-1" x-text="notification.date_human || ''"></p>
                        </div>
                        <template x-if="notification.is_unread">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 mt-1.5 flex-shrink-0"></span>
                        </template>
                    </div>
                </button>
            </template>
        </div>
    </div>
</div>

@once
<script>
    function headerNotificationsWidget(config) {
        return {
            openNotifications: false,
            unreadCount: 0,
            notifications: [],
            timerId: null,

            init() {
                this.fetchNotifications();
                this.timerId = setInterval(() => this.fetchNotifications(), 8000);
            },

            async fetchNotifications() {
                try {
                    const response = await fetch(config.feedUrl, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) return;

                    const data = await response.json();
                    this.unreadCount = Number(data.unreadCount || 0);
                    this.notifications = Array.isArray(data.notifications) ? data.notifications : [];
                } catch (_) {}
            },

            toggle() {
                this.openNotifications = !this.openNotifications;
                if (this.openNotifications) {
                    this.fetchNotifications();
                }
            },

            async markAllAsRead() {
                try {
                    const response = await fetch(config.readAllUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        await this.fetchNotifications();
                    }
                } catch (_) {}
            },

            openNotification(id) {
                window.location.href = `${config.openBaseUrl}/${id}`;
            }
        };
    }
</script>
@endonce
