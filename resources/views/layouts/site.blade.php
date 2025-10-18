<!DOCTYPE html>
<html lang="es" class="__variable_fb8f2c __variable_f910ec antialiased">

<head>
  <meta charSet="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ ($title = trim($__env->yieldContent('title'))) }}{{ $title && !str_contains($title, '-') &&
    !str_contains(strtoupper($title), 'Minerva Labs') ? ' - Minerva Labs' : '' }}</title>
  <meta name="description"
    content="@yield('meta_description', 'Laboratorio de Realidad Virtual - El Futuro de la Educación. Descubre experiencias educativas inmersivas con tecnología de realidad virtual de vanguardia. Transforma la manera de aprender y enseñar en Minerva Labs.')" />
  <meta name="author" content="Minerva Labs" />
  <script src="https://cdn.tailwindcss.com"></script>
  @livewireStyles
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/css/app-site.css', 'resources/js/app.js', 'resources/js/app-site.js'])
</head>

<body
  class="relative min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white overflow-x-hidden">

  <!-- Fondo radial y grid -->
  <div
    class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-900/20 via-slate-900/50 to-slate-900">
  </div>
  <div class="absolute inset-0 bg-grid-white/[0.02] bg-[size:50px_50px]"></div>

  <!-- Partículas flotantes -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl animate-pulse delay-1000">
    </div>
    <div
      class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-500">
    </div>
  </div>

  <!-- Header -->
  <header class="border-b border-white/20 bg-white/10 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
          <!-- Icono Monitor -->
          <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
            <line x1="8" y1="21" x2="16" y2="21"></line>
            <line x1="12" y1="17" x2="12" y2="21"></line>
          </svg>
        </div>
        <span
          class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Minerva
          Labs</span>
      </div>
      <div class="flex items-center gap-4">
        <a href="login" class="hover:text-blue-400">Iniciar Sesión</a>
        <a href="register"
          class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-4 py-2 rounded">Registrarse</a>
      </div>
    </div>
  </header>
  <!-- Hero Section -->

  @yield('content')

  <!-- Footer -->
  <footer class="border-t border-white/20 bg-white/10 backdrop-blur-md py-8 px-4 text-center max-w-7xl mx-auto">
    <div class="flex items-center justify-center gap-2 mb-4">
      <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
        <!-- Monitor icon -->
        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
          <line x1="8" y1="21" x2="16" y2="21"></line>
          <line x1="12" y1="17" x2="12" y2="21"></line>
        </svg>
      </div>
      <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Minerva
        Labs</span>
    </div>
    <p class="text-white/70">
      ©{{ date('Y') }} Minerva Labs. Transformando la educación a través de la realidad
      virtual.
    </p>
  </footer>

  @livewireScripts
  <script>
    document.addEventListener('livewire:initialized', function () {
      Livewire.on('message-success', function (message) {
        Alert(
          '¡Éxito!',
          message,
          'success'
        );
      });

      Livewire.on('message-error', function (message) {
        Alert(
          '¡Error!',
          message,
          'error'
        );
      });
    });
  </script>
</body>

</html>