<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-4xl font-bold text-red-600 mb-4">403</h1>
        <h2 class="text-2xl font-semibold mb-2">Ups!</h2>
        <p class="text-gray-700 mb-6">Kamu tidak memiliki hak untuk masuk ke halaman ini.</p>
        @if(isset($exception) && $exception->getMessage())
            <div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded">
                <strong>Debug:</strong> {{ $exception->getMessage() }}
            </div>
        @endif
        <a href="/admin"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Kembali
            ke Beranda</a>
    </div>
</body>

</html>