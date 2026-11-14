<?php

namespace App\Providers;

use App\Http\Middleware\EnsureUserCanAccessOAuthClient;
use App\Models\Passport\Client;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::useClientModel(Client::class);
        Passport::authorizationView('auth.oauth.authorize');

        Gate::define('admin', fn (User $user): bool => $user->role === User::ROLE_ADMIN);

        $this->app->booted(function (): void {
            Route::getRoutes()
                ->getByName('passport.authorizations.authorize')
                ?->middleware(EnsureUserCanAccessOAuthClient::class);
        });
    }
}
