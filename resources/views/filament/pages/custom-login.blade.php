<div class="min-h-screen flex items-center justify-center relative"
    style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Hide any sidebar or navigation */
        .sidebar,
        nav,
        [role="navigation"],
        .filament-sidebar,
        .filament-main-sidebar {
            display: none !important;
        }

        /* Main container */
        .container {
            width: 100%;
            max-width: 400px;
        }

        /* Login card */
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 48px;
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            background-color: #1e293b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo svg {
            width: 32px;
            height: 32px;
            color: white;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .subtitle {
            color: #64748b;
            font-size: 16px;
            font-weight: 400;
        }

        /* Form */
        .form {
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
        }

        .input {
            width: 100%;
            background-color: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 14px 14px 48px;
            color: #1f2937;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
        }

        .input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .input:focus {
            border-color: #1e293b;
            box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.1);
            transform: translateY(-1px);
        }

        .input:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            accent-color: #2563eb;
        }

        .checkbox-label {
            color: #6b7280;
            font-size: 14px;
            cursor: pointer;
        }

        /* Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: none;
            border-radius: 12px;
            padding: 16px 24px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 41, 59, 0.3);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 41, 59, 0.4);
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(30, 41, 59, 0.3);
        }

        .submit-btn:disabled {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .btn-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .submit-btn:hover .btn-icon {
            transform: translateX(2px);
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Error message */
        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }

        .error-content {
            display: flex;
            align-items: flex-start;
        }

        .error-icon {
            width: 20px;
            height: 20px;
            color: #dc2626;
            margin-right: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .error-title {
            font-size: 14px;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 4px;
        }

        .error-list {
            list-style: none;
            color: #dc2626;
            font-size: 14px;
        }

        .error-list li {
            margin-bottom: 4px;
        }

        /* Footer */
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer-text {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 32px 24px;
            }

            .title {
                font-size: 24px;
            }
        }
    </style>

    <!-- Centered login form -->
    <div class="container">
        <div class="login-card">
            <!-- Header -->
            <div class="header">
                <div class="logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="title">Trijaya Agung</h1>
                <p class="subtitle">Admin Panel</p>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="authenticate" class="form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="label">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                            </path>
                        </svg>
                        <input type="email" id="email" wire:model="email" class="input" placeholder="Enter your email"
                            required>
                    </div>
                    @error('email')
                        <p class="text-sm text-red-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="label">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <input type="password" id="password" wire:model="password" class="input"
                            placeholder="Enter your password" required>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-400 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember" wire:model="remember" class="checkbox">
                    <label for="remember" class="checkbox-label">Remember me</label>
                </div>

                <!-- Submit button -->
                <button type="submit" class="submit-btn">
                    <div class="btn-content">
                        <span wire:loading.remove>


                            <p>Sign In</p>
                        </span>
                        <span wire:loading>

                            Signing in...
                        </span>
                    </div>
                </button>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="error-message">
                        <div class="error-content">
                            <svg class="error-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="error-title">Login Failed</h3>
                                <ul class="error-list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </form>

            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">
                    &copy; {{ date('Y') }} Trijaya Agung. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>