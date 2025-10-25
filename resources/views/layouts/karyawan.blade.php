<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - HRIS MVR</title>

    {{-- Tailwind & Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Konfigurasi Tailwind Dark Mode --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' ? true : false,
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        }
    }"
    x-bind:class="darkMode ? 'dark bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-900'"
    class="transition-colors duration-300 font-sans">

    {{-- Wrapper --}}
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white dark:bg-gray-950 shadow-lg flex flex-col justify-between transition-colors duration-300">
            <div>
                {{-- Logo --}}
                <div class="flex items-center justify-center py-6 border-b border-gray-200 dark:border-gray-800">
                    <h1 class="text-xl font-bold dark:text-white">HRIS MVR</h1>
                </div>

                {{-- Menu --}}
                <nav class="mt-4 px-4 space-y-2 text-sm">
                    <a href="{{ route('karyawan.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl
                            {{ request()->routeIs('karyawan.index') ? 'bg-blue-600 text-white' :
                                'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200' }}">
                        <i class="fa-solid fa-house"></i> Dashboard
                    </a>

                    <a href="{{ route('karyawan.absen') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl
                            {{ request()->routeIs('karyawan.absen') ? 'bg-blue-600 text-white' :
                                'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200' }}">
                        <i class="fa-solid fa-clock"></i> Absensi
                    </a>

                    <a href="{{ route('karyawan.pengajuan') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl
                            {{ request()->routeIs('karyawan.pengajuan') ? 'bg-blue-600 text-white' :
                                'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200' }}">
                        <i class="fa-solid fa-calendar-days"></i> Cuti & Izin
                    </a>

                    {{-- Jadwal kerja ambil dari data_karyawan --}}
                    <a href="{{ route('karyawan.jadwal') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl
                            {{ request()->routeIs('karyawan.jadwal') ? 'bg-blue-600 text-white' :
                                'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200' }}">
                        <i class="fa-solid fa-briefcase"></i> Jadwal Kerja
                    </a>

                    {{-- Dropdown Gaji --}}
                    <div x-data="{ open: false }" class="mt-2">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-xl
                                hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-sack-dollar"></i> Gaji</span>
                            <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="open" x-collapse class="pl-12 mt-1 space-y-1">
                            <a href="#" class="block py-2 hover:underline">Slip</a>
                            <a href="#" class="block py-2 hover:underline">Bon Gaji</a>
                        </div>
                    </div>
                </nav>
            </div>

            {{-- Profil & Toggle Tema --}}
            <div class="border-t border-gray-200 dark:border-gray-800 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff"
                         alt="Avatar" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                {{-- Tombol Toggle Tema + Logout --}}
                <div class="flex flex-col items-center gap-3">
                    <button @click="toggleTheme"
                        class="p-2 rounded-full bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 transition"
                        title="Ganti Tema">
                        <i class="fa-solid text-lg"
                           :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-600'"></i>
                    </button>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-gray-500 hover:text-red-500 transition"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Konten Utama --}}
        <main class="flex-1 p-6 overflow-y-auto transition-colors duration-300">
            @yield('content')
        </main>
    </div>

    {{-- FontAwesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    {{-- Terapkan tema saat load --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</body>
</html>
