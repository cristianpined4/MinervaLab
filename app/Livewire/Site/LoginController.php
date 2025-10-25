<?php

namespace App\Livewire\Site;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Component
{
    public $username;
    public $password;
    public $remember_me = false;
    public $loginError = null;

    public function render()
    {
        return view('livewire.admin.auth.login')
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }

    public function login()
    {
        $this->loginError = null;

        $validatedData = $this->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember_me)) {
            session()->flash('success', 'Inicio de sesión exitoso');
            return redirect()->route('home');
        } else {
            $this->loginError = 'Credenciales incorrectas. Intenta nuevamente.';
        }
    }
}
