<?php

namespace App\Livewire\Site;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Component
{
    use WithPagination, WithFileUploads;

    public $record_id;
    public $fields = [
        'username' => null,
        'first_name' => null,
        'last_name' => null,
        'age' => null,
        'email' => null,
        'phone' => null,
        'password' => null,
        'user_rol' => null,
        'admin' => false,
        'id_faculty' => null,
    ];

    public $other_fields = [
        'password_confirmation' => null,
    ];

    public $file;
    public $search = '';
    public $paginate = 10;
    public $isEditing = false;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        $facultys = DB::table('faculty')->select('id', 'description')->get();

        return view('livewire.admin.auth.register', compact('facultys'))
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }

    public function resetForm()
    {
        $this->reset(['fields', 'record_id', 'file', 'isEditing']);
    }

    public function store()
    {
        // Validate the input data
        $this->validate([
            'fields.username' => 'required|string|max:50|unique:users,username',
            'fields.first_name' => 'required|string|max:100',
            'fields.last_name' => 'required|string|max:100',
            'fields.age' => 'nullable|numeric|min:0',
            'fields.email' => 'required|email|unique:users,email',
            'fields.phone' => 'nullable|string|max:15',
            'fields.password' => 'required|min:6|same:other_fields.password_confirmation',
            'fields.user_rol' => 'nullable|string|max:50',
            'fields.id_faculty' => 'required|exists:faculty,id',
        ], [
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
        ]);

        // Hash the password before storing
        $this->fields['password'] = Hash::make($this->fields['password']);

        DB::beginTransaction();

        try {
            // Insert the new user and get the inserted ID
            $id_user = User::insertGetId([
                'username' => $this->fields['username'],
                'first_name' => $this->fields['first_name'],
                'last_name' => $this->fields['last_name'],
                'age' => $this->fields['age'],
                'email' => $this->fields['email'],
                'phone' => $this->fields['phone'],
                'password' => $this->fields['password'],
                'user_rol' => $this->fields['user_rol'],
                'admin' => $this->fields['admin'],
                'id_faculty' => $this->fields['id_faculty'],
            ]);

            DB::commit();

            // Log the user in immediately after successful registration
            Auth::loginUsingId($id_user);

            session()->flash('success', 'Usuario creado correctamente.');

            // Reset the form after successful registration
            $this->resetForm();

            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
            return;
        }
    }

    public function destroy($id)
    {
        // Find the user and delete
        $user = User::findOrFail($id);

        try {
            $user->delete();
            session()->flash('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
