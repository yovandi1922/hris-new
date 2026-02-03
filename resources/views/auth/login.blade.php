<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-white overflow-hidden">

<div class="relative flex min-h-screen">

    <!-- LEFT BACKGROUND (BLACK DIAGONAL WAVE) -->
    <div class="absolute inset-0 overflow-hidden">
        <svg class="absolute w-full h-full" viewBox="0 0 1200 800" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M 0,0 L 600,0 
                     C 600,30 650,280 500,450 
                     S 100,470 50,800 
                     L 0,800 Z" 
                  fill="#000000" 
                  stroke-linecap="round" 
                  stroke-linejoin="miter"/>
        </svg>
    </div>

    <!-- LEFT CONTENT -->
    <div class="relative z-10 flex w-1/2 items-start justify-start pl-80 pt-40">
        <div class="bg-white rounded-full p-1 shadow-lg flex items-center justify-center">
            <!-- Ganti dengan gambar logo Anda -->
            <img src="{{ asset('img/logo123.png') }}" alt="Logo" class="w-52   h-52 object-contain">
            
            <!-- Atau gunakan SVG dari file -->
            <!-- <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-32 h-32"> -->
            
            <!-- SVG manual (hapus jika pakai gambar) -->
            <!-- <svg width="120" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="85" fill="none" stroke="#000" stroke-width="6"/>
                <path d="M 70 80 Q 55 100 70 120" fill="#000"/>
                <path d="M 100 65 Q 85 100 100 135" fill="#000"/>
                <path d="M 130 80 Q 145 100 130 120" fill="#000"/>
                <ellipse cx="75" cy="100" rx="12" ry="25" fill="#000"/>
                <ellipse cx="125" cy="100" rx="12" ry="25" fill="#000"/>
                <path d="M 100 85 L 115 108 L 85 108 Z" fill="#000"/>
            </svg> -->
        </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="relative z-10 flex w-1/2 items-center justify-center">
        <div class="w-full max-w-sm">

            <h1 class="text-3xl font-bold text-center mb-8">LOGIN</h1>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div class="relative">
                    <input type="email" name="email" required
                        class="w-full border-b border-gray-400 py-2 pl-10 focus:outline-none focus:border-black">
                    <svg class="absolute left-2 top-2.5 w-5 h-5 text-gray-500"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4" stroke-width="2"/>
                    </svg>
                </div>

                <!-- Password -->
                <div class="relative">
                    <input type="password" name="password" required
                        class="w-full border-b border-gray-400 py-2 pl-10 focus:outline-none focus:border-black">
                    <svg class="absolute left-2 top-2.5 w-5 h-5 text-gray-500"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" stroke-width="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-width="2"/>
                    </svg>
                </div>

                <div class="text-right text-sm">
                    <a href="#" class="text-gray-600 hover:text-black">Forgot Password?</a>
                </div>

                <button
                    class="w-full bg-black text-white py-2 rounded-full hover:bg-gray-900 transition">
                    LOGIN
                </button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
