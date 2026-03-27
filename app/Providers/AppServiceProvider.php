<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $helpers = app_path('Helpers/settings.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-roles', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('manage_roles');
        });

        Gate::define('manage-permissions', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('manage_permissions');
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasRole('Admin') || $user->hasPermissionTo('manage_users');
        });

        // Expenses: view (Admin, Manager, Cashier), manage (Admin, Manager)
        Gate::define('expenses.view', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Cashier');
        });
        Gate::define('expenses.manage', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Manager');
        });
    }
}
