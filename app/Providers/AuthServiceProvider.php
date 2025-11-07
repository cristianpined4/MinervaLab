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
        Gate::define('admin', function ($user) {
            $role = Roles::find($user->id_rol);
            return $role && $role->permissions == 1;
        });
    }
}
