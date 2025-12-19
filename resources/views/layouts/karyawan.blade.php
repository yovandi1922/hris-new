<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - HRIS MVR</title>

    {{-- TAILWIND & ALPINE --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Dark mode config --}}
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
        }
    }"
    x-bind:class="darkMode ? 'dark bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-900'"
    class="transition-colors duration-300 font-sans">

<div class="flex min-h-screen">

    {{-- SIDEBAR BARU  --}}
    <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 dark:from-gray-950 dark:to-black
                text-gray-200 shadow-xl flex flex-col justify-between transition-all duration-300">

        <div>
            {{-- LOGO --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-700">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <span class="text-xl font-semibold">paradise.corp</span>
            </div>

            {{-- MENU --}}
            <nav class="mt-4 px-4 text-sm space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('karyawan.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('karyawan.index')
                        ? 'bg-gray-700 text-white'
                        : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-house text-lg"></i>
                    Dashboard
                </a>

                {{-- Absensi --}}
                <a href="{{ route('karyawan.absen') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('karyawan.absen')
                        ? 'bg-gray-700 text-white'
                        : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    Absensi
                </a>

                {{-- Pengajuan --}}
                <div x-data="{ open: {{ request()->routeIs('karyawan.pengajuan') || request()->routeIs('karyawan.lembur') || request()->routeIs('karyawan.bon') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition
                            {{ request()->routeIs('karyawan.pengajuan') || request()->routeIs('karyawan.lembur') || request()->routeIs('karyawan.bon')
                                ? 'bg-gray-700 text-white'
                                : 'hover:bg-gray-700/40 text-gray-300' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-folder text-lg"></i>
                            Pengajuan
                        </span>
                        <i class="fa-solid text-xs transition-transform" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-10 mt-1 space-y-1">

                        <a href="{{ route('karyawan.pengajuan') }}"
                            class="block px-3 py-2 rounded-md transition
                            {{ request()->routeIs('karyawan.pengajuan')
                                ? 'bg-gray-700 text-white'
                                : 'hover:bg-gray-700/40 text-gray-300' }}">
                            <i class="fa-solid fa-file-lines text-sm mr-2"></i>Cuti & Izin
                        </a>

                        <a href="{{ route('karyawan.lembur') }}" class="block px-3 py-2 rounded-md transition
                            {{ request()->routeIs('karyawan.lembur')
                                ? 'bg-gray-700 text-white'
                                : 'hover:bg-gray-700/40 text-gray-300' }}">
                            <i class="fa-solid fa-clock text-sm mr-2"></i>Lembur
                        </a>

                        <a href="{{ route('karyawan.bon') }}" class="block px-3 py-2 rounded-md transition
                            {{ request()->routeIs('karyawan.bon')
                                ? 'bg-gray-700 text-white'
                                : 'hover:bg-gray-700/40 text-gray-300' }}">
                            <i class="fa-solid fa-wallet text-sm mr-2"></i>Bon Gaji
                        </a>
                    </div>
                </div>

                {{-- Jadwal Kerja --}}
                <a href="{{ route('karyawan.jadwal') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('karyawan.jadwal')
                        ? 'bg-gray-700 text-white'
                        : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-calendar text-lg"></i>
                    Jadwal Kerja
                </a>

                {{-- Slip Gaji --}}
                <a href="{{ route('karyawan.slip_gaji') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('karyawan.slip_gaji')
                        ? 'bg-gray-700 text-white'
                        : 'hover:bg-gray-700/40 text-gray-300' }}">
                    <i class="fa-solid fa-dollar-sign text-lg"></i>
                    Slip Gaji
                </a>

                {{-- Toggle Light/Dark Mode --}}
                <button @click="toggleTheme"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition
                    hover:bg-gray-700/40 text-gray-300">
                    <i class="fa-solid fa-moon text-lg"></i>
                    Light Mode
                </button>

            </nav>
        </div>

        {{-- PROFILE AREA --}}
        <div class="p-5 border-t border-gray-700 space-y-4">

            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=444&color=fff"
                     class="w-10 h-10 rounded-full">

                <div>
                    <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="flex flex-col items-center gap-3">

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-red-600/20 hover:bg-red-600/30 transition text-red-400 text-sm">
                        <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>

            </div>
        </div>

    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 p-6 overflow-y-auto transition-colors duration-300">
        @yield('content')
    </main>

</div>

{{-- FONT AWESOME --}}
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
