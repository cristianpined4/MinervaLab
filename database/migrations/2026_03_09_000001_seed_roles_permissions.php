<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Asigna el valor del campo `permissions` a cada rol.
 *
 * Mapa de permisos:
 *  1 → Administrador   (acceso total al panel admin)
 *  2 → Directivo       (acceso intermedio)
 *  3 → Docente         (puede reservar y ver reportes)
 *  4 → Estudiante      (solo puede reservar)
 *  5 → Invitado        (acceso mínimo)
 */
return new class extends Migration {
  public function up(): void
  {
    $map = [
      'Administrador' => 1,
      'Directivo' => 2,
      'Docente' => 3,
      'Estudiante' => 4,
      'Invitado' => 5,
    ];

    foreach ($map as $name => $perm) {
      DB::table('roles')
        ->where('name', $name)
        ->update(['permissions' => $perm]);
    }
  }

  public function down(): void
  {
    DB::table('roles')->update(['permissions' => null]);
  }
};
