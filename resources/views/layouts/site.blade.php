<!DOCTYPE html>
<html lang="es" class="antialiased">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>
    {{ ($title = trim($__env->yieldContent('title'))) }}
    {{ $title && !str_contains($title, '-') && !str_contains(strtoupper($title), 'MINERVA LABS') ? ' - Minerva Labs' : '' }}
  </title>

  <meta name="description"
    content="@yield('meta_description', 'Laboratorio de Realidad Virtual - El Futuro de la Educación. Descubre experiencias educativas inmersivas con tecnología de realidad virtual de vanguardia. Transforma la manera de aprender y enseñar en Minerva Labs.')" />
  <meta name="author" content="Minerva Labs" />

  @livewireStyles
  @vite(['resources/css/app.css', 'resources/css/app-site.css', 'resources/js/app.js', 'resources/js/app-site.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white overflow-x-hidden relative flex">

  {{-- Mostrar menú lateral solo si la vista NO define @section('hide_menu', true) --}}
  @if (!View::hasSection('hide_menu'))
    @include('layouts.components.site-main-menu')
  @endif

  {{-- Contenedor principal (ajusta margen si hay menú o no) --}}
  <div class="@if(!View::hasSection('hide_menu')) lg:ml-64 @endif flex-1 relative">

    <!-- Fondo decorativo -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-900/20 via-slate-900/50 to-slate-900"></div>
    <div class="absolute inset-0 bg-grid-white/[0.02] bg-[size:50px_50px]"></div>

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/50/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <div class="relative z-10">

      {{-- HEADER condicional --}}
      @if (!View::hasSection('hide_header'))
        <header class="border-b border-white/20 bg-white/10 backdrop-blur-md">
          <div class="max-w-7xl mx-3 md:mx-auto py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                  <line x1="8" y1="21" x2="16" y2="21"></line>
                  <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
              </div>
              <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Minerva Labs</span>
            </div>
            <div class="flex items-center gap-4">
              <a href="{{ url('login') }}" class="hover:text-blue-400">Iniciar Sesión</a>
              <a href="{{ url('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-4 py-2 rounded">
                Registrarse
              </a>
            </div>
          </div>
        </header>
      @endif

      {{-- CONTENIDO PRINCIPAL --}}
      <main class="p-6 mx-auto max-w-7xl">
        @yield('content')
      </main>

      {{-- FOOTER condicional --}}
      @if (!View::hasSection('hide_footer'))
        <footer class="border-t border-white/20 bg-white/10 backdrop-blur-md py-8 px-4 text-center max-w-7xl mx-auto">
          <div class="flex items-center justify-center gap-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
              <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
              </svg>
            </div>
            <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Minerva Labs</span>
          </div>
          <p class="text-white/70">
            ©{{ date('Y') }} Minerva Labs. Transformando la educación a través de la realidad virtual.
          </p>
        </footer>
      @endif
    </div>
  </div>

  @livewireScripts

  <script>
    document.addEventListener('livewire:initialized', function() {
      Livewire.on('message-success', message => {
        Alert('¡Éxito!', message, 'success');
      });
      Livewire.on('message-error', message => {
        Alert('¡Error!', message, 'error');
      });
    });
  </script>

  <script>
    (function () {
      const DATE_REGEX = /^(\d{2})\/(\d{2})\/(\d{4})$/;
      const DATETIME_REGEX = /^(\d{2})\/(\d{2})\/(\d{4})\s(\d{2}):(\d{2})\s(AM|PM)$/i;

      function pad(number) {
        return String(number).padStart(2, '0');
      }

      function to12Hour(hour24) {
        const period = hour24 >= 12 ? 'PM' : 'AM';
        const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
        return { hour: pad(hour12), period };
      }

      function normalizeDateValue(value) {
        if (!value) return '';
        const trimmed = value.trim();

        const direct = trimmed.match(DATE_REGEX);
        if (direct) {
          return `${direct[1]}/${direct[2]}/${direct[3]}`;
        }

        const iso = trimmed.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (iso) {
          return `${iso[3]}/${iso[2]}/${iso[1]}`;
        }

        const compact = trimmed.replace(/\D/g, '');
        if (compact.length === 8) {
          return `${compact.slice(0, 2)}/${compact.slice(2, 4)}/${compact.slice(4, 8)}`;
        }

        return trimmed;
      }

      function normalizeDateTimeValue(value) {
        if (!value) return '';
        const trimmed = value.trim().toUpperCase();

        const direct = trimmed.match(DATETIME_REGEX);
        if (direct) {
          return `${direct[1]}/${direct[2]}/${direct[3]} ${direct[4]}:${direct[5]} ${direct[6]}`;
        }

        const isoLocal = trimmed.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(?::\d{2})?$/);
        if (isoLocal) {
          const hour24 = Number(isoLocal[4]);
          const { hour, period } = to12Hour(hour24);
          return `${isoLocal[3]}/${isoLocal[2]}/${isoLocal[1]} ${hour}:${isoLocal[5]} ${period}`;
        }

        const sqlDateTime = trimmed.match(/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2})(?::\d{2})?$/);
        if (sqlDateTime) {
          const hour24 = Number(sqlDateTime[4]);
          const { hour, period } = to12Hour(hour24);
          return `${sqlDateTime[3]}/${sqlDateTime[2]}/${sqlDateTime[1]} ${hour}:${sqlDateTime[5]} ${period}`;
        }

        return trimmed;
      }

      function bindDateInput(input) {
        if (input.dataset.formatBound === '1') return;
        input.dataset.formatBound = '1';
        input.setAttribute('placeholder', input.getAttribute('placeholder') || 'DD/MM/AAAA');
        input.setAttribute('inputmode', 'numeric');
        input.addEventListener('blur', function () {
          input.value = normalizeDateValue(input.value);
          const isValid = !input.value || DATE_REGEX.test(input.value);
          input.setCustomValidity(isValid ? '' : 'Formato requerido: DD/MM/AAAA');
        });
      }

      function bindDateTimeInput(input) {
        if (input.dataset.formatBound === '1') return;
        input.dataset.formatBound = '1';
        input.setAttribute('placeholder', input.getAttribute('placeholder') || 'DD/MM/AAAA 01:01 AM');
        input.addEventListener('input', function () {
          input.value = input.value.toUpperCase();
        });
        input.addEventListener('blur', function () {
          input.value = normalizeDateTimeValue(input.value);
          const isValid = !input.value || DATETIME_REGEX.test(input.value);
          input.setCustomValidity(isValid ? '' : 'Formato requerido: DD/MM/AAAA 01:01 AM/PM');
        });
      }

      function initFormattedInputs() {
        document.querySelectorAll('.js-date-input').forEach(bindDateInput);
        document.querySelectorAll('.js-datetime-input').forEach(bindDateTimeInput);
      }

      document.addEventListener('DOMContentLoaded', initFormattedInputs);
      document.addEventListener('livewire:initialized', initFormattedInputs);
      document.addEventListener('livewire:navigated', initFormattedInputs);
      setTimeout(initFormattedInputs, 250);
    })();
  </script>

</body>
</html>
