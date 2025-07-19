<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

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
        // Ensure API routes always return JSON
        Response::macro('api', function ($data, $status = 200) {
            return Response::json($data, $status, [
                'Content-Type' => 'application/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With'
            ]);
        });

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
