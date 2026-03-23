@section('title', "Usuarios")

<main style="width: 100%;">
    <div class="loading" wire:loading.attr="show" show="false">
        <div class="loader"></div>
        <p class="loading-text">Cargando...</p>
    </div>
    <!-- modales -->
    <div id="form-usuarios" class="modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar usuario' : 'Nuevo usuario' }}</h5>
                    <button type="button" class="btn-close bg-white/20 hover:bg-white/30 border-0" aria-label="Cerrar"
                        onclick="closeModal(this.closest('.modal'))">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre</label>
                        <input wire:model="fields.name" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('fields.name') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback">@error('fields.name') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Apellido</label>
                        <input wire:model="fields.lastname" type="text" placeholder="Apellido" id="apellido"
                            class="form-control @error('fields.lastname') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback">@error('fields.lastname') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input wire:model="fields.email" type="email" placeholder="Correo Electrónico" id="email"
                            class="form-control @error('fields.email') was-validated is-invalid @enderror">
                        <div class="invalid-feedback">@error('fields.email') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de Usuario</label>
                        <input wire:model="fields.username" type="text" placeholder="Nombre de Usuario" id="username"
                            class="form-control @error('fields.username') was-validated is-invalid @enderror">
                        <div class="invalid-feedback">@error('fields.username') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ $record_id ? 'Cambiar ' : '' }}Contraseña</label>
                        <input wire:model="fields.password" type="password" placeholder="Contraseña" id="password"
                            class="form-control @error('fields.password') was-validated is-invalid @enderror">
                        <div class="invalid-feedback">@error('fields.password') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rol</label>
                        <select wire:model="fields.role_id" id="role_id"
                            class="form-control @error('fields.role_id') was-validated is-invalid @enderror">
                            <option value="">-- Seleccione --</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">@error('fields.role_id') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select wire:model="fields.is_active" id="is_active"
                            class="form-control @error('fields.is_active') was-validated is-invalid @enderror">
                            <option value="">-- Seleccione --</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        <div class="invalid-feedback">@error('fields.is_active') {{$message}} @enderror</div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($record_id)
                    <button type="button" class="btn btn-warning bg-yellow-600 hover:bg-yellow-700 text-white border-0" wire:click="update" wire:loading.attr="disabled" wire:target="update">
                        <span wire:loading.remove wire:target="update">Actualizar</span>
                        <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                    </button>
                    @else
                    <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" wire:click="store" wire:loading.attr="disabled" wire:target="store">
                        <span wire:loading.remove wire:target="store">Guardar</span>
                        <span wire:loading wire:target="store"><i class="fas fa-spinner fa-spin"></i> Cargando...</span>
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary bg-white/10 hover:bg-white/20 text-white border border-white/20"
                        onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modales -->

    <!-- Contenido - inicio -->
    <div class="flex justify-between items-end flex-wrap gap-4">
        <div class="flex items-start gap-4 flex-col" style="max-width: 800px;width: 100%;">
            <h2 class="text-xl font-semibold">Módulo Usuarios</h2>
            <input type="text" placeholder="Buscar..." class="form-input" wire:model.live.debounce.500ms="search">
        </div>
        <button class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-0" style="max-width: 200px;" wire:click="abrirModal('form-usuarios')">
            Nuevo Usuario
        </button>
    </div>
    <hr style="margin-top: 20px; margin-bottom: 10px;">
    <div class="overflow-x-auto">
        <table class="table min-w-full bg-white/5 border border-white/10 rounded-lg shadow-sm">
            <thead class="bg-white/5 text-white/60 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Correo Electrónico</th>
                    <th class="px-4 py-3 text-left">Usuario</th>
                    <th class="px-4 py-3 text-left">Rol</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-white text-sm">
                @foreach ($records as $usuario)
                <tr class="border-b hover:bg-white/5">
                    <td class="px-4 py-3">{{ $usuario->id }}</td>
                    <td class="px-4 py-3">{{ $usuario->name }} {{ $usuario->lastname }}</td>
                    <td class="px-4 py-3">{{ $usuario->email }}</td>
                    <td class="px-4 py-3">{{ $usuario->username }}</td>
                    <td class="px-4 py-3">{{ $usuario->role->name }}</td>
                    <td class="px-4 py-3">
                        @if ($usuario->is_active)
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Activo
                        </span>
                        @else
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Inactivo
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex space-x-2 items-center">
                        <button
                            class="bg-white/50 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition text-sm"
                            wire:click="edit('{{ $usuario->id }}')">Editar</button>
                        @if (Auth::user()->id !== $usuario->id && Auth::user()->role_id == 1)
                        <button class="bg-white/50 text-white px-3 py-1 rounded-md hover:bg-red-600 transition text-sm"
                            onclick="confirmarEliminar({{ $usuario->id }})">Eliminar</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $records->links() }}
        </div>
    </div>
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
                    let modelDialog = modalElement.querySelector('.modal-dialog');
                    if (modelDialog) {
                        modelDialog.scrollTop = 0;
                    }
                }
            });
        });

        const confirmarEliminar = async id => {
            if (await window.Confirm(
                'Eliminar',
                '¿Estas seguro de eliminar este Usuario?',
                'warning',
                'Si, eliminar',
                'Cancelar'
            )) {
                Livewire.dispatch('delete', { id });
            }
        }
</script>