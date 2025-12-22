@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="flex-1 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
            Data Absensi Karyawan
        </h1>
        <div class="flex gap-2">
            <input type="text" placeholder="Cari NIP atau Nama..."
                class="border border-gray-300 rounded-lg px-4 py-2 w-64
                       focus:ring-2 focus:ring-blue-500 focus:outline-none
                       dark:bg-gray-700 dark:text-white">
            <button onclick="toggleFilterPanel()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg shadow transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <div class="flex gap-6">

        <!-- Tabel Absensi -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow rounded-lg p-6 overflow-x-auto">

            @if ($absensi->isEmpty())
                <p class="text-gray-500 dark:text-gray-300">
                    Belum ada data absensi.
                </p>
            @else
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Jam Masuk</th>
                            <th class="px-4 py-3 text-left">Jam Keluar</th>
                            <th class="px-4 py-3 text-left">Latitude</th>
                            <th class="px-4 py-3 text-left">Longitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="px-4 py-2 font-semibold">
                                    {{ $item->user->name }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->tanggal }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->jam_masuk ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->jam_keluar ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->latitude ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->longitude ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Panel Filter -->
        <div id="filterPanel"
            class="hidden w-[380px] bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6
                   border border-gray-300 dark:border-gray-700 transition-all duration-300">

            <h2 class="text-lg font-bold mb-4">Filter Absensi</h2>

            <!-- Periode -->
            <div class="mb-4">
                <h3 class="font-medium mb-2">Periode</h3>
                <div class="space-y-2">
                    <label><input type="checkbox" class="mr-2">Hari ini</label>
                    <label><input type="checkbox" class="mr-2">Kemarin</label>
                    <label><input type="checkbox" class="mr-2">Minggu ini</label>
                    <label><input type="checkbox" class="mr-2">Bulan ini</label>
                    <label><input type="checkbox" class="mr-2">Rentang tanggal</label>
                    <input type="date"
                        class="border rounded px-2 py-1 mt-2 w-full
                               dark:bg-gray-700 dark:border-gray-600">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <h3 class="font-medium mb-2">Status</h3>
                <div class="space-y-2">
                    <label><input type="checkbox" class="mr-2">Semua</label>
                    <label><input type="checkbox" class="mr-2">Sudah Clock-in</label>
                    <label><input type="checkbox" class="mr-2">Belum Clock-in</label>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <h3 class="font-medium mb-2">Keterangan</h3>
                <div class="space-y-2">
                    <label><input type="checkbox" class="mr-2">Tepat waktu</label>
                    <label><input type="checkbox" class="mr-2">Terlambat</label>
                    <label><input type="checkbox" class="mr-2">Izin</label>
                    <label><input type="checkbox" class="mr-2">Lembur</label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button onclick="toggleFilterPanel()"
                    class="px-4 py-2 bg-gray-300 rounded-full">
                    Tutup
                </button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-full">
                    Terapkan
                </button>
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
