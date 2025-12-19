<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - HRIS MVR</title>

    {{-- TAILWIND --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ALPINE --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- FONT AWESOME --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }"
    x-bind:class="darkMode ? 'dark bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-900'"
    class="transition-colors duration-300 font-sans">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-gray-200
                shadow-xl flex flex-col justify-between">

        <div>
            {{-- LOGO --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-700">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-house text-white text-lg"></i>
                </div>
                <span class="text-xl font-semibold">paradise.corp</span>
            </div>

            {{-- MENU --}}
            <nav class="mt-4 px-4 text-sm space-y-1">

                <a href="{{ route('karyawan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                   {{ request()->routeIs('karyawan.index') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-house w-5"></i>
                    Dashboard
                </a>

                <a href="{{ route('karyawan.absen') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                   {{ request()->routeIs('karyawan.absen') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-circle-check w-5"></i>
                    Absensi
                </a>

                {{-- PENGAJUAN (TIDAK DIHAPUS) --}}
                <div x-data="{ open: {{ request()->routeIs('karyawan.pengajuan','karyawan.lembur','karyawan.bon') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                        {{ request()->routeIs('karyawan.pengajuan','karyawan.lembur','karyawan.bon')
                            ? 'bg-gray-700 text-white'
                            : 'hover:bg-gray-700/40 text-gray-300' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-folder w-5"></i>
                            Pengajuan
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs" x-show="open"></i>
                        <i class="fa-solid fa-chevron-right text-xs" x-show="!open"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-10 mt-1 space-y-1">
                        <a href="{{ route('karyawan.pengajuan') }}" class="block px-3 py-2 rounded-md hover:bg-gray-700/40">
                            <i class="fa-solid fa-file-lines mr-2"></i>Cuti & Izin
                        </a>
                        <a href="{{ route('karyawan.lembur') }}" class="block px-3 py-2 rounded-md hover:bg-gray-700/40">
                            <i class="fa-solid fa-clock mr-2"></i>Lembur
                        </a>
                        <a href="{{ route('karyawan.bon') }}" class="block px-3 py-2 rounded-md hover:bg-gray-700/40">
                            <i class="fa-solid fa-wallet mr-2"></i>Bon Gaji
                        </a>
                    </div>
                </div>

                <a href="{{ route('karyawan.jadwal') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700/40">
                    <i class="fa-solid fa-calendar w-5"></i>
                    Jadwal Kerja
                </a>

                <a href="{{ route('karyawan.slip_gaji') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700/40">
                    <i class="fa-solid fa-money-bill w-5"></i>
                    Slip Gaji
                </a>

                {{-- TOGGLE MODE + SWITCH --}}
                <button @click="toggleTheme"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-700/40 transition">

                    <div class="flex items-center gap-3">
                        <i class="fa-solid"
                           :class="darkMode ? 'fa-moon' : 'fa-sun'"></i>
                        <span x-text="darkMode ? 'Dark Mode' : 'Light Mode'"></span>
                    </div>

                    <div class="relative w-11 h-6 rounded-full transition"
                         :class="darkMode ? 'bg-blue-500' : 'bg-gray-500'">
                        <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform"
                              :class="darkMode ? 'translate-x-5' : 'translate-x-0'"></span>
                    </div>
                </button>

            </nav>
        </div>

        {{-- PROFILE --}}
        <div class="p-5 border-t border-gray-700 space-y-4">

            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                     class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-red-600/20 hover:bg-red-600/30 text-red-400">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    Logout
                </button>
            </form>

        </div>
    </aside>

    {{-- CONTENT --}}
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

</div>

</body>
</html>
