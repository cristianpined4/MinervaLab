<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAttendanceAccess
{
  public function handle(Request $request, Closure $next): Response
  {
    $user = $request->user();

    if (!$user || !$user->relationLoaded('Rol')) {
      $user?->load('Rol');
    }

    $roleName = strtolower(trim((string) optional($user?->Rol)->name));
    $allowedRoles = ['administrador', 'directivo', 'docente'];

    if (!$user || !in_array($roleName, $allowedRoles, true)) {
      abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    return $next($request);
  }
}