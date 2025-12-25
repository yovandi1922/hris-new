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

        <div class="grid grid-cols-3 gap-10">

            <!-- NAMA -->
            <div x-data="{ edit: false, value: 'Agus Yulianto' }" class="space-y-2">
                <p class="text-gray-500">Nama</p>

                <template x-if="!edit">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-semibold" x-text="value"></p>
                        <button @click="edit = true" class="text-blue-600 hover:underline">Ubah</button>
                    </div>
                </template>

                <template x-if="edit">
                    <div class="space-y-2">
                        <input type="text" x-model="value"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-blue-200">
                        <div class="flex gap-3">
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- NO HP -->
            <div x-data="{ edit: false, value: '(+62) 812 3456 7890' }" class="space-y-2">
                <p class="text-gray-500">Nomor Handphone</p>

                <template x-if="!edit">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-semibold" x-text="value"></p>
                        <button @click="edit = true" class="text-blue-600 hover:underline">Ubah</button>
                    </div>
                </template>

                <template x-if="edit">
                    <div class="space-y-2">
                        <input type="text" x-model="value"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-blue-200">
                        <div class="flex gap-3">
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- EMAIL -->
            <div x-data="{ edit: false, value: 'agus@gmail.com' }" class="space-y-2">
                <p class="text-gray-500">E-mail</p>

                <template x-if="!edit">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-semibold" x-text="value"></p>
                        <button @click="edit = true" class="text-blue-600 hover:underline">Ubah</button>
                    </div>
                </template>

                <template x-if="edit">
                    <div class="space-y-2">
                        <input type="email" x-model="value"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-blue-200">
                        <div class="flex gap-3">
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                            <button @click="edit = false"
                                    class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        </div>
                    </div>
                </template>
            </div>

        </div>

        <!-- JABATAN -->
        <div class="space-y-2">
            <p class="text-gray-500">Jabatan</p>
            <p class="text-xl font-semibold">Administrator</p>
        </div>

        <!-- KEAMANAN -->
        <div class="pt-6 border-t border-gray-200 dark:border-white/10">
            <h2 class="text-xl font-semibold mb-6">Keamanan</h2>

            <div class="grid grid-cols-2 gap-10">

                <div>
                    <p class="text-gray-500">Terakhir Login</p>
                    <p class="font-semibold">10 September 2025, 08 : 32</p>
                </div>

                <!-- PASSWORD -->
                <div x-data="{ edit: false }" class="space-y-2">
                    <p class="text-gray-500">Password</p>

                    <template x-if="!edit">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold tracking-widest">************</p>
                            <button @click="edit = true" class="text-blue-600 hover:underline">Ubah</button>
                        </div>
                    </template>

                    <template x-if="edit">
                        <div class="space-y-2">
                            <input type="password"
                                   placeholder="Password baru"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-blue-200">
                            <div class="flex gap-3">
                                <button @click="edit = false"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                                <button @click="edit = false"
                                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
