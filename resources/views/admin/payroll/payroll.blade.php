@extends('layouts.admin')

@section('title', 'Manajemen Gaji')

@section('content')
<div class="p-8 bg-gray-100 dark:bg-gray-800 min-h-screen font-sans transition-colors duration-300">

    <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-gray-100">Manajemen Gaji</h1>

    <!-- Bagian Pemasukan -->
    <div class="mb-10 bg-white dark:bg-gray-700 shadow-md border border-gray-200 dark:border-gray-600 p-6" style="border-radius:35%;">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-100">Pemasukan</h2>
        <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-100">
                <tr>
                    <th class="border px-4 py-3 dark:border-gray-500">ID</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Nama</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Jumlah</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($salaryComponents['income'] ?? []) as $comp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        <td class="border px-4 py-2 dark:border-gray-500">{{ $comp['id'] }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500">{{ $comp['name'] }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500">Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500 space-x-2">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs">Edit</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-gray-500 dark:text-gray-300">Belum ada data pemasukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">＋ Tambah Pemasukan</button>
    </div>

    <!-- Bagian Potongan -->
    <div class="bg-white dark:bg-gray-700 shadow-md border border-gray-200 dark:border-gray-600 p-6" style="border-radius:35%;">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-100">Potongan</h2>
        <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-100">
                <tr>
                    <th class="border px-4 py-3 dark:border-gray-500">ID</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Nama</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Jumlah</th>
                    <th class="border px-4 py-3 dark:border-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($salaryComponents['deductions'] ?? []) as $comp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        <td class="border px-4 py-2 dark:border-gray-500">{{ $comp['id'] }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500">{{ $comp['name'] }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500">Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2 dark:border-gray-500 space-x-2">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs">Edit</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-gray-500 dark:text-gray-300">Belum ada data potongan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">＋ Tambah Potongan</button>
    </div>
</div>
@endsection
