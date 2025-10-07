<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Karyawan') - HRIS MVR</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <header class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-lg font-bold">HRIS MVR</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Sidebar + Konten -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-white w-64 h-screen shadow-md hidden md:block">
            <nav class="mt-6 space-y-2">
                <a href="{{ route('karyawan.index') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('karyawan.index') ? 'bg-blue-200 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('karyawan.absen') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('karyawan.absen') ? 'bg-blue-200 font-semibold' : '' }}">
                    Absen Kehadiran
                </a>
                <a href="{{ route('karyawan.pengajuan') }}" class="block px-4 py-2 hover:bg-blue-100 {{ request()->routeIs('karyawan.absen') ? 'bg-blue-200 font-semibold' : '' }}">
                    Pengajuan
                </a>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
