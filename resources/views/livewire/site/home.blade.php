@section('title', "Minerva Labs - Laboratorio de Realidad Virtual")

<main class="relative z-10">
    <!-- modales -->
    <div id="modal-home" class="modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar usuario' : 'Nuevo usuario' }}</h5>
                    <button type="button" class="btn-close" aria-label="Cerrar"
                        onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre Completo</label>
                        <input wire:model="fields.name" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('fields.name') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback">@error('fields.name') {{$message}} @enderror</div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($record_id)
                    <button type="button" class="btn btn-warning" wire:click="update">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="store">Guardar</button>
                    @endif
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modales -->

    <!-- Contenido - inicio -->
    <section class="py-20 px-4 text-center max-w-7xl mx-auto">
        <span
            class="mb-6 inline-block bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-full text-sm">Laboratorio
            de Realidad Virtual</span>
        <h1
            class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-400 bg-clip-text text-transparent">
            El Futuro de la Educación
        </h1>
        <p class="text-xl text-white/70 mb-8 max-w-3xl mx-auto leading-relaxed">
            Descubre experiencias educativas inmersivas con tecnología de realidad virtual de vanguardia. Transforma
            la
            manera de aprender y enseñar en Minerva Labs.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="register"
                class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-8 py-3 rounded text-lg flex items-center justify-center gap-2">
                Comenzar Ahora
                <!-- ArrowRight icon -->
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14m-7-7l7 7-7 7"></path>
                </svg>
            </a>
            <button class="text-lg px-8 py-3 border border-white/20 rounded flex items-center justify-center gap-2"
                wire:click="abrirModal('modal-home')">
                <!-- Play icon -->
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="5,3 19,12 5,21"></polygon>
                </svg>
                Ver Demo
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center">

        <!-- Card 1 -->
        <div
            class="bg-white/10 backdrop-blur-sm border border-white/10 p-6 rounded-lg hover:bg-white/20 transition-all duration-300">
            <div
                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mb-4">
                <!-- Monitor icon -->
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <h3 class="text-xl text-white font-bold mb-2">Laboratorios Avanzados</h3>
            <p class="text-white/70 mb-2">3 laboratorios equipados con tecnología VR de última generación</p>
            <ul class="text-sm text-white/70 space-y-2">
                <li>• 30 lentes VR de alta resolución</li>
                <li>• Sistemas de seguimiento 6DOF</li>
                <li>• Audio espacial inmersivo</li>
            </ul>
        </div>

        <!-- Card 2 -->
        <div
            class="bg-white/10 backdrop-blur-sm border border-white/10 p-6 rounded-lg hover:bg-white/20 transition-all duration-300">
            <div
                class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-4">
                <!-- Zap icon -->
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
            <h3 class="text-xl text-white font-bold mb-2">Experiencias Educativas</h3>
            <p class="text-white/70 mb-2">Contenido inmersivo diseñado para potenciar el aprendizaje</p>
            <ul class="text-sm text-white/70 space-y-2">
                <li>• Exploración del sistema solar</li>
                <li>• Anatomía humana en 3D</li>
                <li>• Historia antigua interactiva</li>
            </ul>
        </div>

        <!-- Card 3 -->
        <div
            class="bg-white/10 backdrop-blur-sm border border-white/10 p-6 rounded-lg hover:bg-white/20 transition-all duration-300">
            <div
                class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center mb-4">
                <!-- Users icon -->
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3 class="text-xl text-white font-bold mb-2">Gestión Inteligente</h3>
            <p class="text-white/70 mb-2">Sistema avanzado de reservas y administración</p>
            <ul class="text-sm text-white/70 space-y-2">
                <li>• Reservas automatizadas</li>
                <li>• Seguimiento de asistencia</li>
                <li>• Reportes y estadísticas</li>
            </ul>
        </div>

    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 text-center max-w-4xl mx-auto">
        <h2 class="text-4xl font-bold mb-6">¿Listo para Transformar tu Experiencia Educativa?</h2>
        <p class="text-xl text-white/70 mb-8">Únete a Minerva Labs y descubre el poder de la realidad virtual en la
            educación</p>
        <a href="/auth/register"
            class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-8 py-3 rounded text-lg flex items-center justify-center gap-2">
            Comenzar Gratis
            <!-- ArrowRight icon -->
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M5 12h14m-7-7l7 7-7 7"></path>
            </svg>
        </a>
    </section>
    <!-- Contenido - fin -->
</main>

<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('cerrar-modal', function (modal) {
            let modalElement = document.getElementById(modal[0].modal);
            if (modalElement) {
                closeModal(modalElement);
            }
        });

        Livewire.on('abrir-modal', function (modal) {
            let modalElement = document.getElementById(modal[0].modal);
            if (modalElement) {
                openModal(modalElement);
            }
        });
    });

    const confirmarEliminar = async id => {
        if (await window.Confirm(
            'Eliminar',
            '¿Estas seguro de eliminar este Home?',
            'warning',
            'Si, eliminar',
            'Cancelar'
        )) {
            Livewire.dispatch('delete', { id });
        }
    }
</script>