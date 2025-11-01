<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id_rol = $request->user() ? $request->user()->id_rol : 5; // Asignar 5 (Invitado) si no hay usuario autenticado
        $rol = Roles::find($id_rol);
        if ($rol->permissions != 1) {
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
