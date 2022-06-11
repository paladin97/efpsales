<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Gate::define('teacher', function ($user) {
            if ($user->hasRole('teacher') ){
                return true;
            }
            return false;
        });
        Gate::define('crm', function ($user) {
            if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('comercial') || $user->hasRole('delegate')){
                return true;
            }
            return false;
        });
        Gate::define('admin', function ($user) {
            if ($user->hasRole('superadmin') || $user->hasRole('admin') ){
                return true;
            }
            return false;
        });
        Gate::define('comercial', function ($user) {
            if ( $user->hasRole('comercial') || $user->hasRole('delegate')){
                return true;
            }
            return false;
        });
        Gate::define('delegate', function ($user) {
            if ( $user->hasRole('delegate')){
                return true;
            }
            return false;
        });
    }
}
