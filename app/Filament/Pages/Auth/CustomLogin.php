<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 */
class CustomLogin extends Page
{
    protected static ?string $navigationIcon = null;
    protected static string $view = 'filament.admin.pages.auth.login';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Login';

    public ?string $email = null;
    public ?string $password = null;
    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            redirect()->intended('/admin/statistik');
        }
    }

    public function authenticate(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                $user = Auth::user();

                // Check if user is admin
                if ($user->role !== 'admin') {
                    Auth::logout();
                    $this->addError('email', 'Access denied. Admin privileges required.');
                    return;
                }

                redirect()->intended('/admin/statistik');
            } else {
                $this->addError('email', 'The provided credentials do not match our records.');
            }
        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred during login. Please try again.');
        }
    }
}