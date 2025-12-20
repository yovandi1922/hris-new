@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="flex-1 p-6 bg-gray-100 dark:bg-[#020617] min-h-screen transition-colors">

    <!-- ================= HEADER ================= -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
            Data Absensi Karyawan
        </h1>

        <div class="flex gap-2">
            <!-- Search -->
            <input type="text" placeholder="Cari NIP atau Nama..." 
                   class="w-64 px-4 py-2 rounded-xl
                          border border-gray-300 dark:border-gray-700
                          bg-white dark:bg-[#111827]
                          text-gray-800 dark:text-gray-100
                          focus:ring-2 focus:ring-yellow-400 focus:outline-none">

            <!-- Filter Button -->
            <button onclick="toggleFilterPanel()" 
                    class="px-4 py-2 rounded-xl shadow
                           bg-gray-200 hover:bg-gray-300
                           dark:bg-[#1f2937] dark:hover:bg-[#374151]
                           text-gray-700 dark:text-gray-100 transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <div class="flex gap-6">

        <!-- ================= TABLE ================= -->
        <div class="flex-1 bg-white dark:bg-[#020617]
                    border border-gray-200 dark:border-gray-800
                    shadow-md rounded-3xl p-6 overflow-x-auto">

            @php
                $absensi = [
                    ['nip'=>'001','name'=>'Ahmad Fauzi','tanggal'=>'2025-11-08','jam_masuk'=>'08:00','jam_keluar'=>'17:00','status'=>'Hadir','keterangan'=>'Tepat waktu'],
                    ['nip'=>'002','name'=>'Siti Aisyah','tanggal'=>'2025-11-08','jam_masuk'=>'08:15','jam_keluar'=>'17:00','status'=>'Hadir','keterangan'=>'Terlambat'],
                    ['nip'=>'003','name'=>'Budi Santoso','tanggal'=>'2025-11-08','jam_masuk'=>null,'jam_keluar'=>null,'status'=>'Tidak Hadir','keterangan'=>'Izin'],
                ];
            @endphp

            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-[#111827]
                               text-gray-700 dark:text-gray-200">
                        <th class="px-4 py-3 text-left font-semibold">NIP</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Jam Masuk</th>
                        <th class="px-4 py-3 text-left font-semibold">Jam Keluar</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($absensi as $item)
                        <tr class="border-t border-gray-200 dark:border-gray-800
                                   hover:bg-gray-50 dark:hover:bg-[#111827]
                                   transition cursor-pointer">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['nip'] }}</td>
                            <td class="px-4 py-2 font-semibold text-gray-800 dark:text-gray-100">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $item['tanggal'] }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $item['jam_masuk'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $item['jam_keluar'] ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 text-xs rounded-full
                                    {{ $item['status'] === 'Hadir'
                                        ? 'bg-emerald-100 text-emerald-600'
                                        : 'bg-red-100 text-red-600' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                {{ $item['keterangan'] ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ================= FILTER PANEL ================= -->
        <div id="filterPanel"
             class="hidden w-[380px]
                    bg-white dark:bg-[#020617]
                    border border-gray-200 dark:border-gray-800
                    shadow-2xl rounded-3xl p-6
                    transition-all duration-300">

            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-5">
                Filter Absensi
            </h2>

            <!-- Periode -->
            <div class="mb-5">
                <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Periode</h3>
                <div class="space-y-2 text-gray-600 dark:text-gray-300">
                    <label class="flex items-center gap-2"><input type="checkbox"> Hari ini</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Kemarin</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Minggu ini</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Bulan ini</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Rentang tanggal</label>
                    <input type="date"
                           class="w-full mt-2 px-3 py-2 rounded-xl
                                  bg-gray-100 dark:bg-[#111827]
                                  border border-gray-300 dark:border-gray-700
                                  text-gray-800 dark:text-gray-100">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-5">
                <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Status</h3>
                <div class="space-y-2 text-gray-600 dark:text-gray-300">
                    <label class="flex items-center gap-2"><input type="checkbox"> Semua</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Sudah Clock-in</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Belum Clock-in</label>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Keterangan</h3>
                <div class="space-y-2 text-gray-600 dark:text-gray-300">
                    <label class="flex items-center gap-2"><input type="checkbox"> Tepat waktu</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Terlambat</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Izin</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Lembur</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Belum Absen</label>
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3">
                <button onclick="toggleFilterPanel()"
                        class="px-4 py-2 rounded-xl
                               bg-gray-200 hover:bg-gray-300
                               dark:bg-[#1f2937] dark:hover:bg-[#374151]
                               text-gray-800 dark:text-gray-100 transition">
                    Tutup
                </button>
                <button class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition">
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
