<!DOCTYPE html>
<html lang="id" x-data="appState()" x-init="init()" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Paradise Corp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Filter Sidebar Scroll Fix -->
    <link rel="stylesheet" href="{{ asset('resources/css/filter-fix.css') }}">

    <!-- Custom Styles -->
    <style>
        /* Scrollbar Styling */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(107, 114, 128, 0.4) transparent;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(107, 114, 128, 0.4);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.6);
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden transition-colors duration-300"
      x-data="{
          darkMode: localStorage.getItem('theme') === 'dark',
          sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              document.documentElement.classList.toggle('dark', this.darkMode);
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarOpen', this.sidebarOpen);
          }
      }"
      x-init="darkMode && document.documentElement.classList.add('dark')"
      :class="darkMode ? 'dark bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-900'">

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'w-52 sm:w-64 md:w-72' : 'w-16 sm:w-20 md:w-24'"
        class="fixed left-0 top-0 h-screen shadow-xl flex flex-col transition-all duration-300 z-50"
        :style="darkMode ? 'background: linear-gradient(to bottom, rgb(3, 7, 18), rgb(0, 0, 0))' : 'background: linear-gradient(to bottom, rgb(229, 231, 235), rgb(209, 213, 219))'">

        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- LOGO --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b flex-shrink-0"
                 :class="darkMode ? 'border-gray-700' : 'border-gray-400'">
                <img src="{{ asset('img/logo123.png') }}" alt="Logo"class="w-20" alt="logo">
                <span class="text-xl font-semibold">paradise.corp</span>
            


            </div>

        <!-- NAVIGATION MENU -->
        <nav class="flex-1 px-1.5 sm:px-2 md:px-3 py-3 sm:py-4 md:py-6 space-y-0.5 sm:space-y-1 md:space-y-2 overflow-y-auto" x-data="{ openApproval: false, openPayroll: false }">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base transition"
                :class="{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white font-semibold' : 'bg-gray-400 text-gray-900 font-semibold') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            <!-- Kepegawaian -->
            <a href="{{ route('admin.karyawan.index') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base transition"
                :class="{{ request()->routeIs('admin.karyawan.*') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white font-semibold' : 'bg-gray-400 text-gray-900 font-semibold') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                <i class="fas fa-users w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Kepegawaian</span>
            </a>

            <!-- Absensi -->
            <a href="{{ route('admin.absen') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base transition"
                :class="{{ request()->routeIs('admin.absen') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white font-semibold' : 'bg-gray-400 text-gray-900 font-semibold') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                <i class="fas fa-check-circle w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Absensi</span>
            </a>

            <!-- Persetujuan -->
            <div>
                <button @click="openApproval = !openApproval"
                    class="w-full flex items-center justify-between gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base transition-all duration-200"
                    :class="darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700'">
                    <div class="flex items-center gap-3 md:gap-4">
                        <i class="fas fa-clipboard-check w-5 text-center flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition>Persetujuan</span>
                    </div>
                    <i x-show="sidebarOpen" :class="{ 'rotate-180': openApproval }"
                        class="fas fa-chevron-down transition-transform duration-300 text-xs md:text-sm flex-shrink-0"></i>
                </button>
                <div x-show="openApproval && sidebarOpen" x-transition 
                     class="ml-4 md:ml-6 mt-1 md:mt-2 space-y-1 pl-3 md:pl-4"
                     :class="darkMode ? 'border-l border-gray-700' : 'border-l border-gray-400'">
                    <a href="{{ route('admin.pengajuan.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg transition"
                        :class="darkMode ? 'hover:bg-gray-700/40 text-gray-400' : 'hover:bg-gray-400/30 text-gray-600'">Cuti & Izin</a>
                    <a href="{{ route('admin.lembur.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg transition"
                        :class="darkMode ? 'hover:bg-gray-700/40 text-gray-400' : 'hover:bg-gray-400/30 text-gray-600'">Lembur</a>
                    <a href="{{ route('admin.bon.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg transition"
                        :class="darkMode ? 'hover:bg-gray-700/40 text-gray-400' : 'hover:bg-gray-400/30 text-gray-600'">Bon Gaji</a>
                </div>
            </div>

            <!-- Gaji -->
            <a href="{{ route('admin.slipgaji.index') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base transition"
                :class="{{ request()->routeIs('admin.slipgaji.*') ? 'true' : 'false' }} ? (darkMode ? 'bg-gray-700 text-white font-semibold' : 'bg-gradient-to-r from-white to-gray-100 text-gray-900 shadow-sm font-semibold') : (darkMode ? 'hover:bg-gray-700/40 text-gray-300' : 'hover:bg-gray-400/30 text-gray-700')">
                <i class="fas fa-money-bill-wave w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Gaji</span>
            </a>

            <!-- Light Mode Toggle -->
            <div class="flex items-center justify-between px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg transition"
                 :class="darkMode ? 'hover:bg-gray-700/40' : 'hover:bg-gray-400/30'">
                <div class="flex items-center gap-2 sm:gap-3"
                     :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                    <i class="fa-solid fa-moon text-sm md:text-lg flex-shrink-0"></i>
                    <span x-show="sidebarOpen" x-transition class="text-xs md:text-base">Light Mode</span>
                </div>
                
                <button @click="toggleTheme" x-show="sidebarOpen" x-transition
                    class="relative inline-flex h-5 w-10 md:h-6 md:w-11 items-center rounded-full transition-colors duration-300"
                    :class="darkMode ? 'bg-gray-600' : 'bg-gray-400'">
                    <span class="inline-block h-3 w-3 md:h-4 md:w-4 transform rounded-full bg-white transition-transform duration-300"
                          :class="darkMode ? 'translate-x-1' : 'translate-x-5 md:translate-x-6'"></span>
                </button>
            </div>

        </nav>
        </div>

        <!-- PROFILE & ACTIONS SECTION -->
        <div class="p-2 sm:p-3 md:p-4 border-t flex items-center justify-between flex-shrink-0"
             :class="darkMode ? 'border-gray-700' : 'border-gray-400'">
            <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3 min-w-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&background=444&color=fff"
                    alt="User Avatar" class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded-full flex-shrink-0">
                <div x-show="sidebarOpen" x-transition class="min-w-0 flex-1">
                    <p class="font-semibold text-xs truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                    <p class="text-xs truncate" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0 ml-2">
                @csrf
                <button type="submit"
                    class="p-1.5 sm:p-2 rounded-lg hover:bg-red-600 transition"
                    :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-white'">
                    <i class="fas fa-sign-out-alt text-xs sm:text-sm"></i>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <main :class="sidebarOpen ? 'ml-52 sm:ml-64 md:ml-72' : 'ml-16 sm:ml-20 md:ml-24'"
        class="flex-1 transition-all duration-300 overflow-auto">
        @yield('content')
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Terapkan tema saat load -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    @yield('scripts')

</body>

</html>
