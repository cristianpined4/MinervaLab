<?php

namespace App\Providers;

use App\Models\Roles;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        $this->registerPolicies();

        // Admin: permissions = 1 (Administrador) — con fallback por id_rol = 1
        Gate::define('admin', function ($user) {
            $role = Roles::find($user->id_rol);
            return $role && (intval($role->permissions) === 1 || intval($user->id_rol) === 1);
        });

        // Docente: permissions = 3
        Gate::define('teacher', function ($user) {
            $role = Roles::find($user->id_rol);
            return $role && intval($role->permissions) === 3;
        });

        // Directivo: permissions = 2
        Gate::define('directivo', function ($user) {
            $role = Roles::find($user->id_rol);
            return $role && intval($role->permissions) === 2;
        });

        // Staff con acceso a reportes (admin o directivo)
        Gate::define('reports', function ($user) {
            $role = Roles::find($user->id_rol);
            $perm = $role ? intval($role->permissions) : 99;
            return in_array($perm, [1, 2]);
        });
    }
}