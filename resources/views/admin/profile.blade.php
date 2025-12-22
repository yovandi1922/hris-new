@extends('layouts.admin')

@section('content')

<div class="flex justify-between mb-10">
    <h1 class="text-3xl font-semibold">Profile</h1>
    <span class="text-gray-500">Dashboard / Profile</span>
</div>

<div class="flex gap-16">

    <!-- FOTO -->
    <div class="relative">
        <img src="https://randomuser.me/api/portraits/men/32.jpg"
             class="w-40 h-40 rounded-full border-4 border-white shadow-xl object-cover">
        <button
            class="absolute top-2 right-2 w-9 h-9 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center shadow">
            <i class="fas fa-pen text-sm text-gray-700"></i>
        </button>
    </div>

    <!-- DATA -->
    <div class="flex-1 space-y-10">

        <!-- DATA UTAMA -->
        <div class="grid grid-cols-3 gap-10">

            <!-- Nama -->
            <div class="space-y-2">
                <p class="text-gray-500">Nama</p>
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xl font-semibold">Agus Yulianto</p>
                    <button class="text-blue-600 font-medium hover:underline">
                        Ubah
                    </button>
                </div>
            </div>

            <!-- No HP -->
            <div class="space-y-2">
                <p class="text-gray-500">Nomor Handphone</p>
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xl font-semibold">(+62) 812 3456 7890</p>
                    <button class="text-blue-600 font-medium hover:underline">
                        Ubah
                    </button>
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <p class="text-gray-500">E-mail</p>
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xl font-semibold">agus@gmail.com</p>
                    <button class="text-blue-600 font-medium hover:underline">
                        Ubah
                    </button>
                </div>
            </div>

        </div>

        <!-- Jabatan -->
        <div class="space-y-2">
            <p class="text-gray-500">Jabatan</p>
            <p class="text-xl font-semibold">Administrator</p>
        </div>

        <!-- KEAMANAN -->
        <div class="pt-6 border-t border-gray-200 dark:border-white/10">
            <h2 class="text-xl font-semibold mb-6">Keamanan</h2>

            <div class="grid grid-cols-2 gap-10">

                <div class="space-y-2">
                    <p class="text-gray-500">Terakhir Login</p>
                    <p class="font-semibold">10 September 2025, 08 : 32</p>
                </div>

                <div class="space-y-2">
                    <p class="text-gray-500">Password</p>
                    <div class="flex items-center justify-between gap-4">
                        <p class="font-semibold tracking-widest">************</p>
                        <button class="text-blue-600 font-medium hover:underline">
                            Ubah
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
