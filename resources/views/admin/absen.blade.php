@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="flex-1 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Data Absensi Karyawan</h1>
        <div class="flex gap-2">
            <!-- Search statis -->
            <input type="text" placeholder="Cari NIP atau Nama..." 
                   class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:text-white">
            <!-- Tombol filter -->
            <button onclick="toggleFilterPanel()" 
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg shadow transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <div class="flex gap-6">
        <!-- Tabel Absensi -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow rounded-lg p-6 overflow-x-auto">
            @php
                $absensi = [
                    ['nip'=>'001','name'=>'Ahmad Fauzi','tanggal'=>'2025-11-08','jam_masuk'=>'08:00','jam_keluar'=>'17:00','status'=>'Hadir','keterangan'=>'Tepat waktu'],
                    ['nip'=>'002','name'=>'Siti Aisyah','tanggal'=>'2025-11-08','jam_masuk'=>'08:15','jam_keluar'=>'17:00','status'=>'Hadir','keterangan'=>'Terlambat'],
                    ['nip'=>'003','name'=>'Budi Santoso','tanggal'=>'2025-11-08','jam_masuk'=>null,'jam_keluar'=>null,'status'=>'Tidak Hadir','keterangan'=>'Izin'],
                ];
            @endphp

            <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">NIP</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Masuk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Keluar</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $item['nip'] }}</td>
                            <td class="px-4 py-2 font-semibold text-gray-800 dark:text-gray-100">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['tanggal'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['jam_masuk'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['jam_keluar'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['status'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Panel Filter -->
        <div id="filterPanel" class="hidden w-[380px] bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 border border-gray-300 dark:border-gray-700 transition-all duration-300">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Filter Absensi</h2>

            <!-- Periode -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Periode</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Hari ini</label>
                    <label><input type="checkbox" class="mr-2">Kemarin</label>
                    <label><input type="checkbox" class="mr-2">Minggu ini</label>
                    <label><input type="checkbox" class="mr-2">Bulan ini</label>
                    <label><input type="checkbox" class="mr-2">Rentang tanggal</label>
                    <input type="date" class="border rounded px-2 py-1 mt-2 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Status</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Semua</label>
                    <label><input type="checkbox" class="mr-2">Sudah Clock-in</label>
                    <label><input type="checkbox" class="mr-2">Belum Clock-in</label>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Keterangan</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Semua</label>
                    <label><input type="checkbox" class="mr-2">Tepat waktu</label>
                    <label><input type="checkbox" class="mr-2">Terlambat</label>
                    <label><input type="checkbox" class="mr-2">Izin</label>
                    <label><input type="checkbox" class="mr-2">Lembur</label>
                    <label><input type="checkbox" class="mr-2">Belum Absen</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="toggleFilterPanel()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded-full hover:bg-gray-400">Tutup</button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilterPanel() {
    document.getElementById('filterPanel').classList.toggle('hidden');
}
</script>
@endsection
