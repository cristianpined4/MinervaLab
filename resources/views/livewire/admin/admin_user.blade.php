@section('title', 'Administrar exclusiones')
@section('hide_header', true)
@section('hide_footer', true)

<div>
    @include('layouts.components.header-global')

    <main class="flex flex-col md:flex-row bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
          <!-- modales -->
        <div id="modal-home" class="modal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userLabel">{{ $record_id ? 'Editar exclusion' : 'Agregar exclusion' }}</h5>
                        <button type="button" class="btn-close" aria-label="Cerrar"
                            onclick="closeModal(this.closest('.modal'))">&times;</button>
                    </div>                    <div class="modal-body">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de usuario
                            @error('fields.username')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.username" type="text" placeholder="Nombre de usuario" id="username"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre
                            @error('fields.first_name')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.first_name" type="text" placeholder="Nombre" id="first_name"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido
                            @error('fields.last_name')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.last_name" type="text" placeholder="Apellido" id="last_name"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Edad
                            @error('fields.age')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.age" type="number" placeholder="Edad" id="age"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico
                            @error('fields.email')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.email" type="email" placeholder="Correo electrónico" id="email"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono
                            @error('fields.phone')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.phone" type="text" placeholder="Teléfono" id="phone"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña
                            @error('fields.password')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <input wire:model="fields.password" type="password" placeholder="Contraseña" id="password"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rol de usuario
                            @error('fields.id_rol')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <select wire:model="fields.id_rol" id="id_rol"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">Seleccione un rol</option>
                                @foreach ($id_roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->description }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Facultad
                            @error('fields.id_faculty')
                                <span class="text-red-500 text-sm">* {{ $message }}</span>
                            @enderror</label>
                            <select wire:model="fields.id_faculty" id="id_faculty"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">Seleccione una facultad</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->description }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($record_id)
                            <button type="button" class="btn btn-warning" wire:click="store_update">Actualizar</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store_update">Guardar</button>
                        @endif
                        <button type="button" class="btn btn-secondary" onclick="closeModal(this.closest('.modal'))">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- fin modales -->

        <div class="flex-1 p-6 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto">

                {{-- Header Section con Botón --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Administrar usuarios</h1>
                        <p class="text-gray-600">Administracion de usuarios y permisos</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="abrirModal"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar usuario
                        </button>
                    </div>
                </div>

                {{-- Tabla de Reservaciones --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                    {{-- Header de la tabla --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Lista de Usuarios
                        </h2>
                    </div>
                    <div class="relative w-full border-b-2">
                        <input wire:model.live="search"
                            type="text"
                            placeholder="Buscar exclusión..."
                            class="w-full rounded-lg bg-white text-black placeholder-gray-500 px-4 py-2 text-sm focus:outline-none">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </div>
                    {{-- Tabla responsive --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Apellido
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Correo
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Telefono
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                {{-- Fila ejemplo 1 --}}
                                @foreach ($data as $row)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $row->first_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $row->last_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $row->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $row->phone }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                @if ($row->active == 0)
                                                    <button class="p-1 m-1 btn btn-danger" title="Editar" wire:click="estado({{ $row->id }})">
                                                        Inactivo
                                                    </button>
                                                @else
                                                    <button class="p-1 m-1 btn btn-success" title="Editar" wire:click="estado({{ $row->id }})">
                                                        Activo
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar" wire:click="abrirModal({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar" wire:click="confirmarEliminar({{ $row->id }})">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No tienes reservaciones aún</p>
                                            <p class="text-gray-400 text-sm mt-2">Haz clic en "Agregar Reservación" para crear una nueva</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    {{ $data->links() }}
                    </div>

                    {{-- Footer de la tabla con paginación --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Mostrando <span class="font-semibold text-gray-900">1-3</span> de <span class="font-semibold text-gray-900">3</span> exclusiones
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Anterior
                            </button>
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Siguiente
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

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
                //alert(JSON.stringify(modal))
                let modalElement = document.getElementById(modal[0].modal);
                if (modalElement) {
                    openModal(modalElement);
                    let username = document.getElementById('username');
                    let first_name = document.getElementById('first_name');
                    let last_name = document.getElementById('last_name');
                    let age = document.getElementById('age');
                    let email = document.getElementById('email');
                    let phone = document.getElementById('phone');
                    let password = document.getElementById('password');
                    let id_rol = document.getElementById('id_rol');
                    let id_faculty = document.getElementById('id_faculty');
                    let active = document.getElementById('active');

                    username.value = modal[0].fields.username;
                    first_name.value = modal[0].fields.first_name;
                    last_name.value = modal[0].fields.last_name;
                    age.value = modal[0].fields.age;
                    email.value = modal[0].fields.email;
                    phone.value = modal[0].fields.phone;
                    password.value = modal[0].fields.password;
                    id_rol.value = modal[0].fields.id_rol;
                    id_faculty.value = modal[0].fields.id_faculty;
                    active.checked = modal[0].fields.active;
                }
            });

            Livewire.on('swal:success', e => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: e.message,
                });
            });
            Livewire.on('confirmar-eliminar', data => {
                //alert(JSON.stringify(data))
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.find(@this.id).call('erase', data[0].id);
                    }
                });
            });
            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            });

        });

    </script>
</div>