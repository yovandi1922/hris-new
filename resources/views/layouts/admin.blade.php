<!DOCTYPE html>
<html lang="id"
      x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.darkMode);
        },
        init() {
            if (this.darkMode) document.documentElement.classList.add('dark');
        }
      }"
      x-init="init()"
      class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen flex">

    <!-- Sidebar – sudut melengkung besar seperti gambar Anda -->
    <aside class="w-72 bg-white/70 dark:bg-black/50 backdrop-blur-2xl border-r border-gray-200 dark:border-white/10 flex flex-col shadow-2xl rounded-r-3xl overflow-hidden">

        <div class="flex-1 p-6 overflow-y-auto">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <div class="w-11 h-11 bg-black dark:bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white dark:text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                        <circle cx="9" cy="9" r="1"/>
                        <circle cx="15" cy="9" r="1"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight">paradise.corp</span>
            </div>

            <!-- Navigasi -->
            <nav class="space-y-1" x-data="{ openApproval: false, openPayroll: false }">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.dashboard'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.karyawan.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.karyawan.*'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Kepegawaian</span>
                </a>

                <a href="{{ route('admin.absen') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.absen'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-check-circle w-5 text-center"></i>
                    <span>Absensi</span>
                </a>

                <!-- Persetujuan -->
                <div>
                    <button @click="openApproval = !openApproval"
                            class="w-full flex items-center justify-between gap-4 px-4 py-3 rounded-2xl text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-clipboard-check w-5 text-center"></i>
                            <span>Persetujuan</span>
                        </div>
                        <i :class="{'rotate-180': openApproval}" class="fas fa-chevron-down transition-transform duration-300 text-sm"></i>
                    </button>

                    <div x-show="openApproval" x-transition class="ml-12 mt-2 space-y-1">
                        <a href="{{ route('admin.approval.cutiizin') }}" class="block py-2 px-6 text-sm rounded-xl hover:bg-white/60 dark:hover:bg-white/10">Cuti & Izin</a>
                        <a href="{{ route('admin.approval.lembur') }}" class="block py-2 px-6 text-sm rounded-xl hover:bg-white/60 dark:hover:bg-white/10">Lembur</a>
                        <a href="{{ route('admin.approval.bon') }}" class="block py-2 px-6 text-sm rounded-xl hover:bg-white/60 dark:hover:bg-white/10">Bon Gaji</a>
                    </div>
                </div>

                <a href="{{ route('admin.jadwal') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.jadwal'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-calendar-alt w-5 text-center"></i>
                    <span>Jadwal Kerja</span>
                </a>

                <!-- Gaji -->
                <div>
                    <button @click="openPayroll = !openPayroll"
                            class="w-full flex items-center justify-between gap-4 px-4 py-3 rounded-2xl text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10 transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-money-bill-wave w-5 text-center"></i>
                            <span>Gaji</span>
                        </div>
                        <i :class="{'rotate-180': openPayroll}" class="fas fa-chevron-down transition-transform duration-300 text-sm"></i>
                    </button>

                    <div x-show="openPayroll" x-transition class="ml-12 mt-2 space-y-1">
                        <a href="{{ route('admin.payroll.gaji') }}" class="block py-2 px-6 text-sm rounded-xl hover:bg-white/60 dark:hover:bg-white/10">Slip</a>
                        <a href="{{ route('admin.payroll.bon') }}" class="block py-2 px-6 text-sm rounded-xl hover:bg-white/60 dark:hover:bg-white/10">Bon Gaji</a>
                    </div>
                </div>

                <a href="{{ route('admin.rekap') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.rekap'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-chart-bar w-5 text-center"></i>
                    <span>Rekap</span>
                </a>

                <a href="{{ route('admin.pengaturan') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200
                   @if(request()->routeIs('admin.pengaturan'))
                        bg-white dark:bg-white/10 text-blue-600 shadow-md font-semibold
                   @else
                        text-gray-600 dark:text-gray-300 hover:bg-white/60 dark:hover:bg-white/10
                   @endif">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>

        <!-- Bagian bawah – sudut bawah kanan juga melengkung -->
        <div class="p-6 border-t border-gray-200 dark:border-white/10 rounded-br-3xl">
            <!-- User Profile -->
            <div class="flex items-center gap-4 mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($loggedUser->name ?? 'Guest') }}&background=2563eb&color=fff&rounded=true&size=64"
                     alt="User Avatar"
                     class="w-14 h-14 rounded-full shadow-lg">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">Halo, {{ $loggedUser->name ?? 'Guest' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $loggedUser->email ?? '' }}</p>
                </div>
            </div>

            <!-- Toggle Dark Mode -->
            <button @click="toggleDark()"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl bg-white/50 dark:bg-white/10 hover:bg-white/70 dark:hover:bg-white/20 transition font-medium shadow">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
                <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white transition font-medium shadow">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto">
        @yield('content')
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>