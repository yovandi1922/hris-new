@extends('layouts.admin')

@section('title', 'Manajemen Bonus')

@section('content')
<div class="p-8 bg-gray-100 dark:bg-gray-800 min-h-screen font-sans transition-colors duration-300">

    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Manajemen Bonus</h1>

    <!-- Card Tabel Bonus -->
    <div class="bg-white dark:bg-gray-700 shadow-md border border-gray-200 dark:border-gray-600 p-6" style="border-radius:35%;">

        <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-100">
                <tr>
                    <th class="px-4 py-3 border dark:border-gray-500">ID</th>
                    <th class="px-4 py-3 border dark:border-gray-500">Karyawan</th>
                    <th class="px-4 py-3 border dark:border-gray-500">Jumlah</th>
                    <th class="px-4 py-3 border dark:border-gray-500">Alasan</th>
                    <th class="px-4 py-3 border dark:border-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonuses as $bonus)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    <td class="px-4 py-2 border dark:border-gray-500">{{ $bonus['id'] }}</td>
                    <td class="px-4 py-2 border dark:border-gray-500">{{ $bonus['employee'] }}</td>
                    <td class="px-4 py-2 border dark:border-gray-500">Rp {{ number_format($bonus['amount'], 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border dark:border-gray-500">{{ $bonus['reason'] }}</td>
                    <td class="px-4 py-2 border dark:border-gray-500 space-x-2">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">＋ Tambah Bonus</button>
    </div>
</div>
@endsection
