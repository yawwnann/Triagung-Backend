<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        .login-box {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
            padding: 32px;
        }

        .login-box h2 {
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form wire:submit.prevent="authenticate">
            <div class="form-group">
                <label>Email</label>
                <input type="email" wire:model.defer="email" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" wire:model.defer="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" wire:model.defer="remember">
                    Remember me
                </label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>