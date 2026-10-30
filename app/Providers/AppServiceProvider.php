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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('create-report', fn ($user) => $user->role === 'pelapor');
        Gate::define('view-recap', fn (User $user): bool => in_array($user->role, ['relawan', 'koordinator'], true));
        Gate::define('manage-areas', fn (User $user): bool => $user->role === 'koordinator');
    }
}
