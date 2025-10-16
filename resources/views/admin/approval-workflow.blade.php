@extends('layouts.admin')

@section('title', 'Approval Workflow')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Approval Workflow</h1>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
            Kembali
        </a>
    </div>

    <!-- Tabel Approval -->
    <div class="overflow-x-auto bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg">
        <table class="min-w-full border border-gray-200 dark:border-gray-600 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-600">
                <tr class="text-left text-gray-700 dark:text-gray-200">
                    <th class="py-3 px-4 border dark:border-gray-600">ID</th>
                    <th class="py-3 px-4 border dark:border-gray-600">Karyawan</th>
                    <th class="py-3 px-4 border dark:border-gray-600">Jenis Permohonan</th>
                    <th class="py-3 px-4 border dark:border-gray-600">Tanggal</th>
                    <th class="py-3 px-4 border dark:border-gray-600">Status</th>
                    <th class="py-3 px-4 border dark:border-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-100">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="py-2 px-4 border dark:border-gray-600">1</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Budi Santoso</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Cuti Sakit</td>
                    <td class="py-2 px-4 border dark:border-gray-600">2025-09-10</td>
                    <td class="py-2 px-4 border dark:border-gray-600">
                        <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-sm font-semibold">Pending</span>
                    </td>
                    <td class="py-2 px-4 border dark:border-gray-600 flex gap-2">
                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg shadow">Setujui</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow">Tolak</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="py-2 px-4 border dark:border-gray-600">2</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Siti Aminah</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Cuti Tahunan</td>
                    <td class="py-2 px-4 border dark:border-gray-600">2025-09-12</td>
                    <td class="py-2 px-4 border dark:border-gray-600">
                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-sm font-semibold">Disetujui</span>
                    </td>
                    <td class="py-2 px-4 border dark:border-gray-600 flex gap-2">
                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg shadow">Setujui</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow">Tolak</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="py-2 px-4 border dark:border-gray-600">3</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Andi Pratama</td>
                    <td class="py-2 px-4 border dark:border-gray-600">Cuti Penting</td>
                    <td class="py-2 px-4 border dark:border-gray-600">2025-09-14</td>
                    <td class="py-2 px-4 border dark:border-gray-600">
                        <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-sm font-semibold">Ditolak</span>
                    </td>
                    <td class="py-2 px-4 border dark:border-gray-600 flex gap-2">
                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg shadow">Setujui</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow">Tolak</button>
                    </td>
                </tr>
                <!-- Tambahkan data lain sesuai kebutuhan -->
            </tbody>
        </table>
    </div>
</div>
@endsection
 