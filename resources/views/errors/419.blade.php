<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir - SIPENDUDUK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md text-center space-y-4">
        <div class="text-6xl">⏳</div>
        <h1 class="text-2xl font-bold text-green-800">Sesi Berakhir</h1>
        <p class="text-gray-600">Sesi Anda telah berakhir. Silakan login kembali.</p>
        <a href="{{ route('login') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
            Login Kembali
        </a>
    </div>
</body>
</html>