@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Data Absensi Karyawan</h1>

    <div class="flex gap-6">
        <!-- BAGIAN TABEL -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @if ($absensi->isEmpty())
                <p class="text-gray-500 dark:text-gray-300 text-center">Belum ada data absensi.</p>
            @else
            <div class="overflow-x-auto">
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer"
                                onclick="openFilterPanel('{{ $item->user->name }}')">
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $item->user->nip }}</td>
                                <td class="px-4 py-2 font-semibold text-gray-800 dark:text-gray-100">{{ $item->user->name }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->tanggal }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->jam_masuk ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->jam_keluar ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->status ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- PANEL FILTER DI SEBELAH KANAN -->
        <div id="filterPanel" class="hidden w-[380px] bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 border border-gray-300 dark:border-gray-700 transition-all duration-300">
            <h2 id="panelTitle" class="text-lg font-bold text-gray-800 dark:text-white mb-4"></h2>

            <!-- PERIODE -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Periode</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Hari ini</label><br>
                    <label><input type="checkbox" class="mr-2">Kemarin</label><br>
                    <label><input type="checkbox" class="mr-2">Minggu ini</label><br>
                    <label><input type="checkbox" class="mr-2">Bulan ini</label><br>
                    <label><input type="checkbox" class="mr-2">Rentang tanggal</label>
                    <input type="date" class="border rounded px-2 py-1 mt-2 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Status</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Semua</label><br>
                    <label><input type="checkbox" class="mr-2">Sudah Clock-in</label><br>
                    <label><input type="checkbox" class="mr-2">Belum Clock-in</label>
                </div>
            </div>

            <!-- KETERANGAN -->
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Keterangan</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <label><input type="checkbox" class="mr-2">Semua</label><br>
                    <label><input type="checkbox" class="mr-2">Tepat waktu</label><br>
                    <label><input type="checkbox" class="mr-2">Terlambat</label><br>
                    <label><input type="checkbox" class="mr-2">Izin</label><br>
                    <label><input type="checkbox" class="mr-2">Lembur</label><br>
                    <label><input type="checkbox" class="mr-2">Belum Absen</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeFilterPanel()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded-full hover:bg-gray-400">Tutup</button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<script>
function openFilterPanel(nama) {
    const panel = document.getElementById('filterPanel');
    const title = document.getElementById('panelTitle');
    title.textContent = 'Filter Data - ' + nama;
    panel.classList.remove('hidden');
}

function closeFilterPanel() {
    const panel = document.getElementById('filterPanel');
    panel.classList.add('hidden');
}
</script>
@endsection
