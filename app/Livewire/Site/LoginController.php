<?php

namespace App\Livewire\Site;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        ->layout('layouts.loginAndRegister');
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
            if(User::find(Auth::id())->active){
                session()->flash('success', 'Inicio de sesión exitoso');
                return redirect()->route('home');
            }else{
                $this->loginError = 'El usuario no esta disponible. Contacta al administrador.';
            }
        } else {
            $this->loginError = 'Credenciales incorrectas. Intenta nuevamente.';
        }
    }
    public function destroy(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}
}
