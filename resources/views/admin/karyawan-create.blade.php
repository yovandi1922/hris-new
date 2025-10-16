@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            👤 Tambah Karyawan
        </h1>
        <a href="{{ route('admin.karyawan') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow transition">
            ← Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">
            Formulir Data Karyawan
        </h2>

        <form action="{{ route('admin.karyawan.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
                <input type="text" name="name"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              dark:bg-gray-700 dark:text-gray-100 shadow-sm" 
                       placeholder="Masukkan nama karyawan" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="email" name="email"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              dark:bg-gray-700 dark:text-gray-100 shadow-sm" 
                       placeholder="Masukkan email karyawan" required>
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              dark:bg-gray-700 dark:text-gray-100 shadow-sm" 
                       placeholder="Masukkan password" required>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              dark:bg-gray-700 dark:text-gray-100 shadow-sm" 
                       placeholder="Ulangi password" required>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.karyawan') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg shadow transition">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
