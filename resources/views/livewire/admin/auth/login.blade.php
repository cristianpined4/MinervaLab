@section('title', 'Iniciar Sesión')

<div class="container" id="loginForm">
    <div class="loading" wire:loading.attr="show" show="false">
        <div class="loader"></div>
        <p class="loading-text">Cargando...</p>
    </div>
    <div class="header">
        <div class="logo" style="font-size: 1rem">MINERVA LAB</div>
        <h2>Iniciar Sesión</h2>
    </div>
    <div class="form-container">
        <form wire:submit.prevent="login">
            <div class="form-group">
                <label for="loginEmail">Usuario</label>
                <input wire:model="username" type="text" placeholder="Usuario" id="loginEmail"
                    class="form-control @error('username') is-invalid @enderror">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="loginPassword">Contraseña</label>
                <input wire:model="password" type="password" placeholder="Contraseña" id="loginPassword"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="rememberMe" class="form-check-label">
                    <input wire:model="remember_me" type="checkbox" id="rememberMe" class="form-check-input">
                    Recordarme
                </label>
            </div>
            @if ($loginError)
                <div class="alert alert-danger">{{ $loginError }}</div>
            @endif
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <a class="toggle-link" href="{{ route('register') }}">¿No tienes cuenta? Crear cuenta</a>
            <a class="toggle-link" href="{{ route('home-site') }}">Volver al sitio</a>
        </form>
    </div>
</div>
