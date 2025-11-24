<?php

namespace App\Livewire\Site;

use App\Models\AdminAttendance;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AccessController extends Component
{
    public $username;
    public $password;
    public $remember_me = false;
    public $fields = [
        'key' => '',
    ];
    public $key;
    public $loginError = null;

    public function render()
    {
        return view('livewire.admin.auth.access')
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }
    public function login()
    {
        $this->loginError = null;

        $validated = $this->validate([
            'key' => 'required',
        ], [
            'key.required' => 'La clave de acceso es obligatoria.',
        ]);

        $registro = AdminAttendance::select('id','key')->first();

        if (!$registro) {
            $this->loginError = 'No hay registro de clave en la base de datos.';
            return;
        }

        if ($registro->key !== $this->key) {
            $this->loginError = 'La clave de acceso es incorrecta.';
            return;
        }

        // Guardar en sesión de Laravel
        session([
            'attendance_id'  => $registro->id,
            'attendance_key' => $registro->key,
        ]);

        session()->flash('success', 'Acceso exitoso.');
        return redirect()->route('set-attendance');
    }


}
