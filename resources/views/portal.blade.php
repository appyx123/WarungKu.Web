<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal login untuk Admin dan Customer - WarungKu">
    <title>Portal Pilihan - WarungKu</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 w-full max-w-lg text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">Selamat Datang di WarungKu</h1>

        <div class="flex flex-col space-y-4">
            <a href="{{ url('admin/login') }}"
               class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-3 px-6 rounded-lg btn transition-colors"
               role="button" aria-label="Login sebagai Admin">
                Login sebagai Admin
            </a>

            <a href="{{ url('customer/login') }}"
               class="bg-blue-400 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg btn transition-colors"
               role="button" aria-label="Login sebagai Customer">
                Login sebagai Customer
            </a>

            <a href="{{ url('customer/register') }}"
               class="bg-green-400 hover:bg-green-500 text-white font-semibold py-3 px-6 rounded-lg btn transition-colors"
               role="button" aria-label="Register sebagai Customer">
                Register sebagai Customer
            </a>
        </div>

        <p class="mt-6 text-sm text-gray-500">© {{ date('Y') }} WarungKu. Hak cipta dilindungi.</p>
    </div>

    <style>
        .btn {
            transition: all 0.3s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.15);
        }
        .container {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
