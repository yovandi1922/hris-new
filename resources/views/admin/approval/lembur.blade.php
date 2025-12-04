@extends('layouts.admin')
@section('title', 'Lembur')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">Lembur</h1>

{{-- SEARCH + FILTER BUTTON (kanan atas) --}}
<div class="flex items-center justify-end gap-3 mb-6">
    <div class="relative w-80">
        <input type="text" placeholder="Search Data Pengajuan Lembur"
               class="w-full px-4 py-2 rounded-full border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
        <i class="fa fa-search absolute right-4 top-2.5 text-gray-500 dark:text-gray-300"></i>
    </div>

    <button onclick="toggleFilter()"
        class="px-4 py-2 rounded-lg border dark:border-gray-600 dark:text-white flex items-center gap-2">
        <i class="fa fa-filter"></i>
    </button>
</div>

{{-- FILTER PANEL --}}
<div id="filter-panel"
     class="hidden fixed inset-0 bg-black/40 flex justify-end items-start pt-24 pr-10 z-50">


    <div class="w-[420px] bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold dark:text-white">Filter</h2>
            <button onclick="toggleFilter()" class="text-gray-700 dark:text-gray-300 text-xl">×</button>
        </div>

        {{-- STATUS --}}
        <div class="mb-4">
            <p class="font-semibold dark:text-gray-200">Status</p>
            <div class="mt-2 grid grid-cols-2 gap-2 dark:text-gray-300">
                <label><input type="checkbox"> Disetujui</label>
                <label><input type="checkbox"> Ditolak</label>
                <label><input type="checkbox"> Menunggu</label>
            </div>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-6">
            <p class="font-semibold dark:text-gray-200">Tanggal</p>
            <input type="date"
                class="mt-2 w-full px-3 py-2 rounded-lg border dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        {{-- ACTION --}}
        <div class="flex justify-between">
            <button class="px-4 py-2 border rounded-lg dark:text-white dark:border-gray-600">Reset</button>
            <button class="px-4 py-2 bg-black text-white rounded-lg">Terapkan</button>
        </div>
    </div>
</div>

{{-- TABLE LIST LEMBUR --}}
<div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">
    <table class="w-full">
        <thead>
            <tr class="text-left text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                <th class="py-3">Nama</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>

        <tbody class="dark:text-gray-100">

            <tr onclick="window.location='/persetujuan/lembur/detail'"
                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 border-b dark:border-gray-700">
                <td class="py-3">Rina Marlina</td>
                <td>21 September 2025</td>
                <td><span class="text-yellow-600 dark:text-yellow-400">Menunggu</span></td>
                <td class="text-right text-gray-500">Lihat Detail →</td>
            </tr>

            <tr onclick="window.location='/persetujuan/lembur/detail'"
                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 border-b dark:border-gray-700">
                <td class="py-3">Agus Widanta</td>
                <td>18 September 2025</td>
                <td><span class="text-green-600">Disetujui</span></td>
                <td class="text-right text-gray-500">Lihat Detail →</td>
            </tr>

        </tbody>
    </table>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }
</script>

@endsection
