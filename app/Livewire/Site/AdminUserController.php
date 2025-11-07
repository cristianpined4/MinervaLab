<?php
namespace App\Livewire\Site;

use App\Models\Faculty;
use App\Models\Roles;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

class AdminUserController extends Component
{
    public $record_id;
    public $fields = [
        'username' => null,
        'first_name' => null,
        'last_name' => null,
        'age' => null,
        'email' => null,
        'phone' => null,
        'password' => null,
        'id_rol' => null,
        'id_faculty' => null,
        'active' => false
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;
    public $faculties = [];
    public $id_roles = [];

    public function render()
    {
        $this->faculties = Faculty::get();
        $this->id_roles = Roles::get();
        $query = User::query();
        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new User())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }
        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_user', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }


    //Modales
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();
        if ($id) {
            $this->record_id = $id;
            $registro = User::find($id);
            $this->fields['username'] = $registro->username;
            $this->fields['first_name'] = $registro->first_name;
            $this->fields['last_name'] = $registro->last_name;
            $this->fields['age'] = $registro->age;
            $this->fields['email'] = $registro->email;
            $this->fields['phone'] = $registro->phone;
            $this->fields['password'] = $registro->password;
            $this->fields['id_rol'] = $registro->id_rol;
            $this->fields['id_faculty'] = $registro->id_faculty;
            $this->fields['active'] = $registro->active;

        } else {
            $this->record_id = null;
            $this->fields = [
                'username' => null,
                'first_name' => null,
                'last_name' => null,
                'age' => null,
                'email' => null,
                'phone' => null,
                'password' => null,
                'id_rol' => null,
                'id_faculty' => null,
                'active' => false
            ];
        }
        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields
        ]);
    }
    public function confirmarEliminar($id)
    {
        $this->dispatch('confirmar-eliminar', [
            'id' => $id,
            'title' => 'Eliminar',
            'text' => '¿Estás seguro de eliminar este registro?',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function erase($id)
    {
        User::find($id)->delete();
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
    }
    public function estado($id)
    {
        $user = User::find($id);
        $user->active = !$user->active;
        $user->save();
        $this->dispatch('swal:notify', ['message' => 'Estado actualizado']);
    }

    public function store_update()
    {

        if($this->record_id != null){
            $this->validate([
                'fields.username' => 'required|string|max:50|unique:users,username,' . $this->record_id,
                'fields.first_name' => 'required|string|max:100',
                'fields.last_name' => 'required|string|max:100',
                'fields.age' => 'nullable|numeric|min:0',
                'fields.email' => 'required|email|unique:users,email,' . $this->record_id,
                'fields.phone' => 'nullable|string|max:15',
                'fields.password' => 'required|min:6',
                'fields.id_rol' => 'required',
                'fields.id_faculty' => 'required'
            ],
            [
                'fields.username.required' => 'El nombre de usuario es obligatorio.',
                'fields.username.unique' => 'Este nombre de usuario ya está en uso.',
                'fields.first_name.required' => 'El primer nombre es obligatorio.',
                'fields.last_name.required' => 'El apellido es obligatorio.',
                'fields.email.required' => 'El correo electrónico es obligatorio.',
                'fields.email.email' => 'El correo electrónico debe ser válido.',
                'fields.email.unique' => 'Este correo electrónico ya está registrado.',
                'fields.password.required' => 'La contraseña es obligatoria.',
                'fields.password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'fields.password.same' => 'Las contraseñas no coinciden.',
                'fields.id_faculty.required' => 'La facultad es obligatoria.',
                'fields.id_faculty.exists' => 'La facultad seleccionada no existe.',
                'fields.id_rol.required' => 'Seleccione el rol del usuario'
            ]
            );

        }else{
            $this->validate([
                'fields.username' => 'required|string|max:50|unique:users,username',
                'fields.first_name' => 'required|string|max:100',
                'fields.last_name' => 'required|string|max:100',
                'fields.age' => 'nullable|numeric|min:0',
                'fields.email' => 'required|email|unique:users,email',
                'fields.phone' => 'nullable|string|max:15',
                'fields.password' => 'required|min:6',
                'fields.id_rol' => 'required',
                'fields.id_faculty' => 'required'
            ],
            [
                'fields.username.required' => 'El nombre de usuario es obligatorio.',
                'fields.username.unique' => 'Este nombre de usuario ya está en uso.',
                'fields.first_name.required' => 'El primer nombre es obligatorio.',
                'fields.last_name.required' => 'El apellido es obligatorio.',
                'fields.email.required' => 'El correo electrónico es obligatorio.',
                'fields.email.email' => 'El correo electrónico debe ser válido.',
                'fields.email.unique' => 'Este correo electrónico ya está registrado.',
                'fields.password.required' => 'La contraseña es obligatoria.',
                'fields.password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'fields.password.same' => 'Las contraseñas no coinciden.',
                'fields.id_faculty.required' => 'La facultad es obligatoria.',
                'fields.id_faculty.exists' => 'La facultad seleccionada no existe.',
                'fields.id_rol.required' => 'Seleccione el rol del usuario'
            ]
            );
        }
        try {
            DB::beginTransaction();
            if($this->record_id != null){
                if(User::find($this->record_id)->pluck('password')->first() != Hash::make($this->fields['password'])){
                    $this->fields['password'] = Hash::make($this->fields['password']);
                }
                User::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
                DB::commit();
            }else{
                $this->fields['password'] = Hash::make($this->fields['password']);
                User::create($this->fields);
                DB::commit();
                $this->dispatch('swal:notify', ['message' => 'Registro creado correctamente']);
            }
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            dd($th);
            return json_encode(['status' => 'error', 'message' => 'Ocurrio un error al guardar el registro']);
        }
    }

}
