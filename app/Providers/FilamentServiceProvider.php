<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure Filament uses session authentication, not JWT
        Auth::viaRequest('filament', function ($request) {
            // Check if user is authenticated via session
            if (Auth::check()) {
                return Auth::user();
            }

            // If not authenticated, return null
            return null;
        });

        // Handle Filament authentication errors
        $this->app->singleton('filament.auth', function ($app) {
            return new class {
                public function authenticate($credentials)
                {
                    try {
                        // Use session authentication for Filament
                        if (Auth::attempt($credentials)) {
                            return Auth::user();
                        }
                        return null;
                    } catch (\Exception $e) {
                        Log::error('Filament auth error', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return null;
                    }
                }
            };
        });
    }
}