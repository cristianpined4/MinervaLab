@section('title', 'Inicio')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="relative z-10 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <!-- Funciones del sistema -->
        <section id="noticias" class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 md:px-6">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Panel de administrador</h2>
                        <p class="text-gray-600"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                    $opciones = [
                        [
                        'titulo' => 'Calendarios',
                        'descripcion' => 'Administrar calendarios, dias feriados y disponibilidad de reservas.',
                        'fecha' => 'Hace 2 horas',
                        'categoria' => 'Sistema',
                        'color' => 'blue',
                        'icono' => 'fa-calendar',
                        'link' => '#'
                        ]
                    ];
                    @endphp
                    @foreach($opciones as $option)
                    <a href="{{ route('admin-calendary') }}"
                    class="block bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all group">
                        <div class="flex flex-col sm:flex-row">
                            <div class="sm:w-1/4 flex items-center justify-center h-48 sm:h-auto bg-gradient-to-br from-{{ $option['color'] }}-400 to-{{ $option['color'] }}-600">
                                <i class="fa-solid {{ $option['icono'] }} text-white text-3xl"></i>
                            </div>
                            <div class="sm:w-3/4 p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-{{ $option['color'] }}-600 transition-colors">
                                    {{ $option['titulo'] }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $option['descripcion'] }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>
            </div>
        </section>



    </main>
    @include('layouts.components.footer-global')

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

</div>