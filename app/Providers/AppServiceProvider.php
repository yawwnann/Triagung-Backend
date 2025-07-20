<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        // Hapus macro Response::api agar tidak override header CORS

        // Handle exceptions for API routes
        $this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', function ($app) {
            return new class ($app) extends \Illuminate\Foundation\Exceptions\Handler {
                public function render($request, \Throwable $e)
                {
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json([
                            'error' => 'Internal server error',
                            'message' => $e->getMessage()
                        ], 500);
                    }

                    return parent::render($request, $e);
                }
            };
        });
    }
}
