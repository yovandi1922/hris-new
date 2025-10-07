<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - HRIS MVR</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 hidden md:flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold text-center mb-4">HRIS MVR</h2>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                       Dashboard
                    </a>
                    <a href="{{ route('admin.karyawan') }}" 
                       class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.karyawan') ? 'bg-gray-700' : '' }}">
                       Karyawan
                    </a>
                    <a href="{{ route('admin.absen') }}" 
                       class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.absen') ? 'bg-gray-700' : '' }}">
                       Absensi
                    </a>
                    <a href="{{ route('admin.approval-workflow') }}" 
                       class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.approval-workflow') ? 'bg-gray-700' : '' }}">
                       Approval
                    </a>
                    <!-- Dropdown Gaji & Bonus -->
                    <div x-data="{ open: false }" class="space-y-1">
                        <button @click="open = !open" 
                                class="w-full flex justify-between items-center py-2.5 px-4 rounded hover:bg-gray-700 focus:outline-none 
                                {{ request()->routeIs('admin.payroll') || request()->routeIs('admin.bonus') ? 'bg-gray-700' : '' }}">
                            <span>Payroll</span>
                            <svg :class="{ 'rotate-180': open }" 
                                 class="w-4 h-4 transform transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Submenu -->
                        <div x-show="open" x-transition class="ml-4 space-y-1">
                            <a href="{{ route('admin.payroll') }}" 
                               class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.payroll') ? 'bg-gray-700' : '' }}">
                               Gaji
                            </a>
                            <a href="{{ route('admin.bonus') }}" 
                               class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.bonus') ? 'bg-gray-700' : '' }}">
                               Bonus
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Tombol Logout -->
            <form action="{{ route('logout') }}" method="POST" class="px-2">
                @csrf
                <button type="submit" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-center">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Konten -->
        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>

    </div>
</body>
</html>
