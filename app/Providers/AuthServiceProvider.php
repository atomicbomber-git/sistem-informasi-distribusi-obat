<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    const EDIT_ADMIN_USER = "EDIT_ADMIN_USER";
    const CAN_VIEW_AUDIT_DATA = "CAN_VIEW_AUDIT_DATA";

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(self::CAN_VIEW_AUDIT_DATA, function (User $user) {
            return in_array($user->level, [User::LEVEL_ADMIN, User::LEVEL_PEGAWAI]);
        });
    }
}
