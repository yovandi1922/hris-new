<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - HRIS MVR</title>
    <!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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
    <aside class="w-64 bg-gradient-to-b shadow-xl flex flex-col transition-all duration-300 h-screen sticky top-0"
           :class="darkMode ? 'from-gray-950 to-black text-gray-200' : 'from-gray-200 to-gray-300 text-gray-800'">

        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- LOGO --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b flex-shrink-0"
                 :class="darkMode ? 'border-gray-700' : 'border-gray-400'">
                <img src="{{ asset('img/logo123.png') }}" alt="Logo"class="w-20" alt="logo">
                <span class="text-xl font-semibold">paradise.corp</span>
            </div>
        

            {{-- MENU --}}
            <nav class="mt-4 px-4 text-sm space-y-1 flex-1 overflow-y-auto">

                {{-- Dashboard --}}
                <a href="{{ route('karyawan.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition"
                    :class="{{ request()->routeIs('karyawan.index') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                    <i class="fa-solid fa-house text-lg"></i>
                    Dashboard
                </a>

                {{-- Absensi --}}
                <a href="{{ route('karyawan.absen') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition"
                    :class="{{ request()->routeIs('karyawan.absen') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                    <i class="fa-solid fa-clock text-lg"></i>
                    Absensi
                </a>

                {{-- Pengajuan --}}
<div x-data="{ open: {{ request()->routeIs('pengajuan.*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition"
        :class="darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700'">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-folder text-lg"></i>
            Pengajuan
        </span>
        <i class="fa-solid text-xs" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
    </button>

    <div x-show="open" x-collapse class="ml-10 mt-1 space-y-1">

        <a href="{{ route('pengajuan.karyawan') }}"
            class="block px-3 py-2 rounded-md transition"
            :class="{{ request()->routeIs('pengajuan.karyawan') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
            Cuti & Izin
        </a>

       

                        <a href="{{ route('karyawan.lembur') }}" class="block px-3 py-2 rounded-md transition"
                            :class="{{ request()->routeIs('karyawan.lembur') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                            Lembur
                        </a>

                        <a href="{{ route('karyawan.bon') }}" class="block px-3 py-2 rounded-md transition"
                            :class="{{ request()->routeIs('karyawan.bon') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                            Bon Gaji
                        </a>
                    </div>
                </div>

                {{-- Jadwal Kerja --}}
                <a href="{{ route('karyawan.jadwal') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition"
                    :class="{{ request()->routeIs('karyawan.jadwal') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gray-400 text-gray-900') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                    <i class="fa-solid fa-calendar-days text-lg"></i>
                    Jadwal Kerja
                </a>
                <a href="{{ route('karyawan.gaji') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition"
                    :class="{{ request()->routeIs('karyawan.gaji') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white' : 'bg-gradient-to-r from-white to-gray-100 text-gray-900 shadow-sm') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                    <i class="fa-solid fa-money-bill-wave text-lg"></i>
                    Slip Gaji
                </a>

                {{-- Light Mode Toggle --}}
                <div class="flex items-center justify-between px-4 py-3 rounded-lg transition"
                     :class="darkMode ? 'hover:bg-gray-700/40' : 'hover:bg-gray-400/30'">
                    <div class="flex items-center gap-3"
                         :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                        <i class="fa-solid fa-moon text-lg"></i>
                        <span>Light Mode</span>
                    </div>
                    
                    {{-- Toggle Switch --}}
                    <button @click="toggleTheme"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300"
                        :class="darkMode ? 'bg-gray-600' : 'bg-gray-400'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300"
                              :class="darkMode ? 'translate-x-1' : 'translate-x-6'"></span>
                    </button>
                </div>

            </nav>
        </div>

        {{-- PROFILE AREA --}}
        <div class="p-5 border-t flex items-center justify-between flex-shrink-0"
             :class="darkMode ? 'border-gray-700' : 'border-gray-400'">

            <div class="flex items-center gap-3 min-w-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=444&color=fff"
                     class="w-10 h-10 rounded-full flex-shrink-0">

                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs truncate" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ auth()->user()->email }}</p>

                </div>
            </div>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0 ml-2">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket text-gray-400 hover:text-red-400 transition"></i>
                </button>
            </form>

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
