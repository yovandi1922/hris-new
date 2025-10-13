<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Karyawan') - HRIS MVR</title>

    {{-- Tailwind & Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Aktifkan dark mode berbasis class --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 font-sans transition-colors duration-300">

    <!-- Navbar -->
    <header class="bg-blue-600 dark:bg-gray-800 text-white shadow-md transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-lg font-bold">HRIS MVR</h1>
            <div class="flex items-center gap-4">
                {{-- Tombol Toggle Tema --}}
                <button id="theme-toggle" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-2 rounded-lg transition">
                    🌓
                </button>

                {{-- Tombol Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar + Konten -->
    <div class="flex min-h-screen transition-colors duration-300">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md h-screen hidden md:block transition-colors duration-300">
            <div class="p-4 text-xl font-bold border-b border-gray-200 dark:border-gray-700 dark:text-white">
                Menu
            </div>
            <nav class="mt-4 space-y-1">
                <a href="{{ route('karyawan.index') }}"
                   class="block px-4 py-2 rounded-md
                          hover:bg-blue-100 dark:hover:bg-gray-700
                          {{ request()->routeIs('karyawan.index') ? 'bg-blue-200 dark:bg-gray-700 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                    Dashboard
                </a>

                <a href="{{ route('karyawan.absen') }}"
                   class="block px-4 py-2 rounded-md
                          hover:bg-blue-100 dark:hover:bg-gray-700
                          {{ request()->routeIs('karyawan.absen') ? 'bg-blue-200 dark:bg-gray-700 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                    Absen Kehadiran
                </a>

                <a href="{{ route('karyawan.pengajuan') }}"
                   class="block px-4 py-2 rounded-md
                          hover:bg-blue-100 dark:hover:bg-gray-700
                          {{ request()->routeIs('karyawan.pengajuan') ? 'bg-blue-200 dark:bg-gray-700 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                    Pengajuan
                </a>

                <a href="{{ route('karyawan.data') }}"
                   class="block px-4 py-2 rounded-md
                          hover:bg-blue-100 dark:hover:bg-gray-700
                          {{ request()->routeIs('karyawan.data') ? 'bg-blue-200 dark:bg-gray-700 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                    Data Karyawan
                </a>
            </nav>
        </aside>

        <!-- Konten Utama -->
        {{-- Di bagian konten utama, matikan efek dark agar tulisan tetap hitam --}}
        <main class="flex-1 p-6 bg-gray-100 text-gray-900 transition-colors duration-300">
            @yield('content')
        </main>
    </div>

    <script>
        // Script toggle tema
        const toggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        // Ambil preferensi tema dari localStorage
        if (localStorage.getItem('theme') === 'dark') {
            html.classList.add('dark');
        }

        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    </script>

</body>
</html>
