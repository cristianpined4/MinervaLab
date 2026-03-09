<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkUser
{
    /**
     * Permite el acceso solo a usuarios con permissions = 1 (Administrador).
     * Usa el role id como fallback si permissions fuera null.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No autenticado.');
        }

        $rol = Roles::find($user->id_rol);

        // Acepta permissions == 1 (Administrador) o id_rol == 1 como fallback
        $isAdmin = $rol && (intval($rol->permissions) === 1 || intval($user->id_rol) === 1);

        if (!$isAdmin) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

