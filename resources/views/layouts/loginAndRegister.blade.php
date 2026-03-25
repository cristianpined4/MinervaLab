<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MINERVA LAB</title>

    @livewireStyles

    @vite([
    'resources/css/app-site.css',
    'resources/css/app-admin.css',
    'resources/js/app.js',
    'resources/js/app-admin.js'
    ])
</head>

<body class="min-h-screen w-full" style="background: linear-gradient(to bottom right, #0a0f2c, #0c1c3f, #091325);">

    @yield('content')

    @livewireScripts
</body>

</html>