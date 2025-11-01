@section('title', "Crear una cuenta")

<main style="width: 100%; max-width: 600px;padding: 1rem;">
    <div class="loading" wire:loading.attr="show" show="false">
        <div class="loader"></div>
        <p class="loading-text">Cargando...</p>
    </div>

    <div class="container" style="margin: 1rem; max-width: 100%;" id="registerForm">
        <div class="header">
            <div class="logo" style="font-size: 1rem">MINERVA LAB</div>
            <h2>Crear Cuenta</h2>
        </div>
        <div class="form-container">
            <form>
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input wire:model="fields.username" type="text" placeholder="Nombre de usuario" id="username"
                        class="form-control @error('fields.username') was-validated is-invalid @enderror"
                        onkeyup="this.value = this.value.toLowerCase();">
                    <div class="invalid-feedback">@error('fields.username') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="first_name">Nombre</label>
                    <input wire:model="fields.first_name" type="text" placeholder="Apellido" id="first_name"
                        class="form-control @error('fields.first_name') was-validated is-invalid @enderror"
                        onkeyup="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">
                    <div class="invalid-feedback">@error('fields.first_name') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="last_name">Apellido</label>
                    <input wire:model="fields.last_name" type="text" placeholder="Apellido" id="last_name"
                        class="form-control @error('fields.last_name') was-validated is-invalid @enderror"
                        onkeyup="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">
                    <div class="invalid-feedback">@error('fields.last_name') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input wire:model="fields.email" type="email" placeholder="Correo electrónico" id="email"
                        class="form-control @error('fields.email') was-validated is-invalid @enderror"
                        onkeyup="this.value = this.value.toLowerCase();">
                    <div class="invalid-feedback">@error('fields.email') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input wire:model="fields.password" type="password" placeholder="Contraseña" id="password"
                        class="form-control @error('fields.password') was-validated is-invalid @enderror">
                    <div class="invalid-feedback">@error('fields.password') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input wire:model="other_fields.password_confirmation" type="password" placeholder="Confirmar Contraseña"
                        id="password_confirmation"
                        class="form-control @error('other_fields.password_confirmation') was-validated is-invalid @enderror">
                    <div class="invalid-feedback">@error('other_fields.password_confirmation') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="id_faculty">Facultad </label>
                    <select wire:model="fields.id_faculty" id="id_faculty"
                        class="form-control @error('fields.id_faculty') was-validated is-invalid @enderror">
                        <option value="">Seleccione</option>
                        @foreach ($facultys as $row)
                        <option value="{{ $row->id }}">{{ $row->description }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">@error('fields.id_faculty') {{$message}} @enderror</div>
                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input wire:model="fields.phone" type="text" placeholder="Teléfono" id="phone"
                        class="form-control @error('fields.phone') was-validated is-invalid @enderror"
                        onkeyup="return /[0-9]/.test(String.fromCharCode(event.keyCode))">
                    <div class="invalid-feedback">@error('fields.phone') {{$message}} @enderror</div>
                </div>
                <button type="button" class="btn btn-primary" wire:click="store">Crear Cuenta</button>
                <a class="toggle-link" href="{{ route('login') }}">
                    ¿Ya tienes cuenta? Iniciar sesión
                </a>
                <a class="toggle-link" href="{{ route('home-site') }}">
                    Volver al sitio</a>
            </form>
        </div>
    </div>
</main>

<script>

</script>