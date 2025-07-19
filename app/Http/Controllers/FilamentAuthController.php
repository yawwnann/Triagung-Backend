<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FilamentAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('filament.pages.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        try {
            // Validate the request
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to authenticate
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $user = Auth::user();

                // Check if user is admin
                if ($user->role !== 'admin') {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Access denied. Admin privileges required.',
                    ]);
                }

                // Log successful login
                Log::info('Filament login successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);

                // Redirect to admin dashboard
                return redirect()->intended(route('filament.admin.pages.dashboard'));
            }

            // Log failed login attempt
            Log::warning('Filament login failed', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);

        } catch (ValidationException $e) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Filament login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'An error occurred during login. Please try again.']);
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Filament logout', [
            'user_id' => $user?->id,
            'email' => $user?->email,
            'ip' => $request->ip()
        ]);

        return redirect()->route('filament.admin.auth.login');
    }
}