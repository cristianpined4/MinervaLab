@section('title', 'Contacto')
@section('meta_description', 'Encuentra la información de contacto de las principales áreas académicas, administrativas y de apoyo.')

<div>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100">
        <div class="container mx-auto px-4 py-24 lg:py-32 text-center">
            <h1 class="text-4xl lg:text-6xl font-bold leading-tight">
                <span class="bg-blue-600 hover:bg-blue-700 text-white">Contactos</span> Institucionales
            </h1>
            <p class="text-lg text-white/60 max-w-2xl mx-auto mt-4 leading-relaxed">
                Encuentra la información de contacto de las principales áreas académicas, administrativas y de apoyo.
            </p>
        </div>
    </section>

    <!-- Directorio -->
    <section class="py-20 bg-white/5">
        <div class="container mx-auto px-8 max-w-7xl">
            <h2 class="text-3xl font-bold text-center mb-14">
                <span class="bg-blue-600 hover:bg-blue-700 text-white">Directorio</span> de Dependencias Universitarias
            </h2>

            <div class="grid gap-14 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($contacts as $contact)
                    <div class="bg-white/5 rounded-2xl border border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-white/10 group">
                        <img src="{{ $contact['image'] }}" alt="{{ $contact['name'] }}" 
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                        <div class="p-6">
                            <div class="flex items-center justify-between text-sm text-white/50 mb-2">
                                <span class="{{ $contact['category_color'] }} font-medium px-3 py-1 rounded-full text-xs">
                                    {{ $contact['category'] }}
                                </span>
                                <div class="flex items-center text-white/60">
                                    <!-- Ícono de teléfono -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 bg-blue-600 hover:bg-blue-700 text-white" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.07 8.81 19.79 19.79 0 0 1 0 0.18 2 2 0 0 1 2.18 0h3a2 2 0 0 1 2 1.72c.13 1.21.44 2.38.93 3.49a2 2 0 0 1-.45 2.11L6.09 9.91a16 16 0 0 0 8 8l2.59-1.54a2 2 0 0 1 2.11-.45c1.11.49 2.28.8 3.49.93a2 2 0 0 1 1.72 2z"/>
                                    </svg>
                                    {{ $contact['phone'] }}
                                </div>
                            </div>

                            <h3 class="text-xl font-semibold text-white mb-2 group-hover:bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                {{ $contact['name'] }}
                            </h3>

                            <p class="text-white/60 mb-3 leading-relaxed">
                                {{ $contact['description'] }}
                            </p>

                            <p class="bg-blue-600 hover:bg-blue-700 text-white font-medium mb-4">
                                {{ $contact['email'] }}
                            </p>

                            <a href="mailto:{{ $contact['email'] }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold hover:underline">
                                Leer más
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
