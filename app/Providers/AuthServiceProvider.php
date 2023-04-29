<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        //Gateの設定
        //1:管理者、5:運営者、9:ユーザー

        Gate::define('admin', function($user){
            return $user->role === 1;
        });
        Gate::define('manager-higher', function($user){
            return $user->role > 0 && $user->role <= 5;
        });
        Gate::define('user-higher', function($user){
            return $user->role > 0 && $user->role <= 9;
        });            
    }
}
