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

<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'w-52 sm:w-64 md:w-72' : 'w-16 sm:w-20 md:w-24'"
        class="fixed left-0 top-0 h-screen bg-white/70 dark:bg-black/50 backdrop-blur-xl border-r border-gray-200 dark:border-white/10 flex flex-col transition-all duration-300 z-50 shadow-lg">

        <!-- LOGO SECTION -->
        <div class="flex items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-black dark:bg-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-white dark:text-black" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <circle cx="9" cy="9" r="1" />
                        <circle cx="15" cy="9" r="1" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-base md:text-xl font-bold whitespace-nowrap">paradise.corp</span>
            </div>
            <button @click="toggleSidebar()" class="text-gray-600 dark:text-gray-300 flex-shrink-0">
                <i :class="sidebarOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right'"></i>
            </button>
        </div>

        <!-- NAVIGATION MENU -->
        <nav class="flex-1 px-1.5 sm:px-2 md:px-3 py-3 sm:py-4 md:py-6 space-y-0.5 sm:space-y-1 md:space-y-2 overflow-y-auto" x-data="{ openApproval: false, openPayroll: false }">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base @if(request()->routeIs('admin.dashboard')) active bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold @else text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 @endif transition">
                <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            <!-- Kepegawaian -->
            <a href="{{ route('admin.karyawan.index') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base @if(request()->routeIs('admin.karyawan.*')) active bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold @else text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 @endif transition">
                <i class="fas fa-users w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Kepegawaian</span>
            </a>

            <!-- Absensi -->
            <a href="{{ route('admin.absen') }}"
                class="flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base @if(request()->routeIs('admin.absen')) active bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold @else text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 @endif transition">
                <i class="fas fa-check-circle w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Absensi</span>
            </a>

            <!-- Persetujuan -->
            <div>
                <button @click="openApproval = !openApproval"
                    class="w-full flex items-center justify-between gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 transition-all duration-200">
                    <div class="flex items-center gap-3 md:gap-4">
                        <i class="fas fa-clipboard-check w-5 text-center flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition>Persetujuan</span>
                    </div>
                    <i x-show="sidebarOpen" :class="{ 'rotate-180': openApproval }"
                        class="fas fa-chevron-down transition-transform duration-300 text-xs md:text-sm flex-shrink-0"></i>
                </button>
                <div x-show="openApproval && sidebarOpen" x-transition class="ml-4 md:ml-6 mt-1 md:mt-2 space-y-1 border-l border-gray-300 dark:border-gray-700 pl-3 md:pl-4">
                    {{-- Link pengajuan cuti/izin dinonaktifkan sementara jika route tidak ada --}}
                    <a href="{{ route('admin.pengajuan.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg hover:bg-white/60 dark:hover:bg-white/10 text-gray-600 dark:text-gray-400 transition">Cuti & Izin</a>
                    <a href="{{ route('admin.lembur.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg hover:bg-white/60 dark:hover:bg-white/10 text-gray-600 dark:text-gray-400 transition">Lembur</a>
                    <a href="{{ route('admin.bon.index') }}"
                        class="block py-1 md:py-2 px-2 md:px-3 text-xs md:text-sm rounded-lg hover:bg-white/60 dark:hover:bg-white/10 text-gray-600 dark:text-gray-400 transition">Bon Gaji</a>
                </div>
            </div>

            <!-- Gaji -->
            <div>
                <a href="{{ route('admin.slipgaji.index') }}"
                    class="w-full flex items-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-3 rounded-lg md:rounded-xl text-xs sm:text-sm md:text-base text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 transition-all duration-200">
                    <div class="flex items-center gap-3 md:gap-4">
                        <i class="fas fa-money-bill-wave w-5 text-center flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition>Gaji</span>
                    </div>
                </a>
            </div>

        </nav>

        <!-- PROFILE & ACTIONS SECTION -->
        <div class="p-1.5 sm:p-2 md:p-4 border-t border-gray-200 dark:border-white/10 space-y-1.5 sm:space-y-2">
            <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&background=2563eb&color=fff&rounded=true&size=40"
                    alt="User Avatar" class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded-full flex-shrink-0">
                <div x-show="sidebarOpen" x-transition>
                    <p class="font-semibold text-xs">{{ auth()->user()->name ?? 'Guest' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>

            <!-- Toggle Dark Mode -->
            <button @click="toggleDark()"
                class="w-full flex items-center justify-center gap-1 py-1 sm:py-1.5 md:py-2 rounded-lg md:rounded-xl bg-white/50 dark:bg-white/10 hover:bg-white/70 dark:hover:bg-white/20 transition font-medium text-xs">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="flex-shrink-0 text-xs sm:text-sm"></i>
                <span x-show="sidebarOpen" x-transition>
                    <span x-text="darkMode ? 'Light' : 'Dark'"></span>
                </span>
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-1 py-1 sm:py-1.5 md:py-2 rounded-lg md:rounded-xl bg-red-600 hover:bg-red-700 text-white transition font-medium text-xs">
                    <i class="fas fa-sign-out-alt flex-shrink-0 text-xs sm:text-sm"></i>
                    <span x-show="sidebarOpen" x-transition>Logout</span>
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

    <!-- Alpine.js Application State -->
    <script>
        function appState() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',

                toggleDark() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', this.darkMode);
                },

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sidebarOpen', this.sidebarOpen);
                },

                init() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }
                }
            };
        }
    </script>

    @yield('scripts')

</body>

</html>
