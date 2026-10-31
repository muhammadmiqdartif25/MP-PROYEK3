<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Paginator::defaultView('vendor.pagination.default');
        Paginator::defaultSimpleView('vendor.pagination.default');

        Gate::define('manage-areas', fn (User $user): bool => $user->role === 'koordinator');
        Gate::define('submit-report', fn (User $user): bool => $user->role === 'pelapor');
        Gate::define('review-report', fn (User $user): bool => in_array($user->role, ['relawan', 'koordinator'], true));
    }
}
