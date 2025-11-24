<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md rounded-[32px] border border-emerald-500/40
                bg-[#050816]/90 shadow-[0_28px_80px_rgba(15,23,42,0.95)]
                px-8 py-12 text-center">

        {{-- ICONO CHECK VERDE --}}
        <div class="flex justify-center mb-6">
            <div class="h-24 w-24 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-400/40">
                <svg viewBox="0 0 24 24" class="h-14 w-14 text-emerald-400">
                    <path fill="none" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-semibold text-emerald-300 mb-3">
            Asistencia registrada
        </h1>

        <p class="text-sm text-slate-300 mb-8">
            Tu asistencia ha sido guardada correctamente.
        </p>

        {{-- BOTÓN PARA CERRAR --}}
        <span class="w-full ps-2 pe-2 p-1 h-11 rounded-xl bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition">
            Ya puedes cerrar esta ventana
        </span>
    </div>

    {{-- SCRIPT PARA CERRAR LA PÁGINA --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-window', () => {
                window.close();
            });
        });
    </script>

</div>
