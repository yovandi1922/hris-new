<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false }" x-bind:class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - HRIS MVR</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} },
        }
    </script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 w-64 p-4 flex flex-col justify-between rounded-r-2xl shadow-lg">
            <div>
                <!-- Logo -->
                <div class="flex items-center space-x-2 mb-8 px-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">PC</span>
                    </div>
                    <span class="text-xl font-semibold">Paradise.corp</span>
                </div>

                <!-- Navigasi -->
                <nav class="space-y-2" x-data="{ openApproval: false, openPayroll: false }">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.dashboard')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-home mr-3"></i> Dashboard
                    </a>

                    <a href="{{ route('admin.karyawan.index') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.karyawan')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-users mr-3"></i> Kepegawaian
                    </a>

                    <a href="{{ route('admin.absen') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.absen')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-check-circle mr-3"></i> Absensi
                    </a>

                    <!-- Approval Dropdown -->
                    <div>
                        <button @click="openApproval = !openApproval" class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="flex items-center">
                                <i class="fas fa-clipboard-check mr-3"></i> Approval
                            </span>
                            <svg :class="{ 'rotate-180': openApproval }" class="w-4 h-4 transform transition-transform duration-200"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openApproval" x-transition class="ml-10 mt-2 space-y-1">
                            <a href="{{ route('admin.approval.cutiizin') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cuti & Izin</a>
                            <a href="{{ route('admin.approval.lembur') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Lembur</a>
                            <a href="{{ route('admin.approval.bon') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Bon Gaji</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.jadwal') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.jadwal')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-calendar-alt mr-3"></i> Jadwal Kerja
                    </a>

                    <!-- Payroll Dropdown -->
                    <div>
                        <button @click="openPayroll = !openPayroll" class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-3"></i> Payroll
                            </span>
                            <svg :class="{ 'rotate-180': openPayroll }" class="w-4 h-4 transform transition-transform duration-200"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openPayroll" x-transition class="ml-10 mt-2 space-y-1">
                            <a href="{{ route('admin.payroll.gaji') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Gaji</a>
                            <a href="{{ route('admin.payroll.bon') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Bon Gaji</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.rekap') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.rekap')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-file-alt mr-3"></i> Rekap
                    </a>

                    <a href="{{ route('admin.pengaturan') }}" class="flex items-center px-4 py-2 rounded-lg transition
                        @if(request()->routeIs('admin.pengaturan')) bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-white font-semibold 
                        @else hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 @endif">
                        <i class="fas fa-cog mr-3"></i> Pengaturan
                    </a>

                    <h1 class="text-center mt-6 text-sm text-gray-500 dark:text-gray-300">
                        Halo, {{ $loggedUser->name ?? 'Guest' }}
                    </h1>
                </nav>
            </div>

            <!-- Tombol Mode -->
            <div>
                <button @click="darkMode = !darkMode"
                        class="w-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 py-2 px-4 rounded-lg">
                    <i :class="darkMode ? 'fas fa-sun mr-2' : 'fas fa-moon mr-2'"></i>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten -->
        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')
</body>
</html>
