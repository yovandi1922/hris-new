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
            <form method="GET" action="{{ route('admin.absen') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP atau Nama..."
                    class="border border-gray-300 rounded-lg px-4 py-2 w-64
                           focus:ring-2 focus:ring-blue-500 focus:outline-none
                           dark:bg-gray-700 dark:text-white">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
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
                            <th class="px-4 py-3 text-left">NIP</th>
                            <th class="px-4 py-3 text-left">Nama Karyawan</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Jam Masuk</th>
                            <th class="px-4 py-3 text-left">Jam Keluar</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Keterangan</th>
                            <th class="px-4 py-3 text-left">Lembur</th>
                        </tr>
                    </thead>
                    <tbody>
@php
    // Jam kerja normal, misal 17:00 (ubah sesuai kebutuhan)
    $jamKerjaNormal = '17:00:00';
@endphp
                        @foreach ($absensi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="px-4 py-2 font-semibold">
                                    {{ $item->user->nip ?? '-' }}
                                </td>
                                <td class="px-4 py-2 font-semibold">
                                    {{ $item->user->name ?? '-' }}
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
                                    @if($item->jam_masuk && !$item->jam_keluar)
                                        Clock In
                                    @elseif($item->jam_masuk && $item->jam_keluar)
                                        Clock Out
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($item->jam_masuk && $item->jam_keluar)
                                        @if($item->jam_masuk > '08:00:00')
                                            Terlambat
                                        @else
                                            Tepat Waktu
                                        @endif
                                    @elseif($item->jam_masuk && !$item->jam_keluar)
                                        Hadir (Belum pulang)
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($item->jam_keluar && $item->jam_keluar > '16:00:00')
                                        @php
                                            $jamKeluar = \Carbon\Carbon::parse($item->jam_keluar);
                                            $jamBatas = $jamKeluar->copy()->setTime(16,0,0);
                                            $lembur = $jamKeluar->gt($jamBatas) ? $jamBatas->diffInMinutes($jamKeluar) : 0;
                                            $jam = floor($lembur / 60);
                                        @endphp
                                        @if($jam >= 1)
                                            {{ $jam }} jam
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif
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
            <form method="GET" action="{{ route('admin.absen') }}" class="space-y-4">
                <!-- Periode -->
                <div class="mb-4">
                    <h3 class="font-medium mb-2">Periode</h3>
                    <div class="space-y-2">
                        <label><input type="checkbox" name="periode[]" value="today" class="mr-2" {{ collect(request('periode'))->contains('today') ? 'checked' : '' }}>Hari ini</label>
                        <label><input type="checkbox" name="periode[]" value="yesterday" class="mr-2" {{ collect(request('periode'))->contains('yesterday') ? 'checked' : '' }}>Kemarin</label>
                        <label><input type="checkbox" name="periode[]" value="this_week" class="mr-2" {{ collect(request('periode'))->contains('this_week') ? 'checked' : '' }}>Minggu ini</label>
                        <label><input type="checkbox" name="periode[]" value="this_month" class="mr-2" {{ collect(request('periode'))->contains('this_month') ? 'checked' : '' }}>Bulan ini</label>
                        <label><input type="checkbox" name="periode[]" value="range" class="mr-2" {{ collect(request('periode'))->contains('range') ? 'checked' : '' }}>Rentang tanggal</label>
                        <input type="text" name="daterange" value="{{ request('daterange') }}" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="border rounded px-2 py-1 mt-2 w-full dark:bg-gray-700 dark:border-gray-600" onfocus="(this.type='date')" onblur="(this.type='text')">
                    </div>
                </div>
                <!-- Status -->
                <div class="mb-4">
                    <h3 class="font-medium mb-2">Status</h3>
                    <div class="space-y-2">
                        <label><input type="checkbox" name="status[]" value="clockin" class="mr-2" {{ collect(request('status'))->contains('clockin') ? 'checked' : '' }}>Sudah Clock-in</label>
                        <label><input type="checkbox" name="status[]" value="noclockin" class="mr-2" {{ collect(request('status'))->contains('noclockin') ? 'checked' : '' }}>Belum Clock-in</label>
                    </div>
                </div>
                <!-- Keterangan -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Keterangan</h3>
                    <div class="space-y-2">
                        <label><input type="checkbox" name="keterangan[]" value="tepat" class="mr-2" {{ collect(request('keterangan'))->contains('tepat') ? 'checked' : '' }}>Tepat waktu</label>
                        <label><input type="checkbox" name="keterangan[]" value="terlambat" class="mr-2" {{ collect(request('keterangan'))->contains('terlambat') ? 'checked' : '' }}>Terlambat</label>
                        <label><input type="checkbox" name="keterangan[]" value="lembur" class="mr-2" {{ collect(request('keterangan'))->contains('lembur') ? 'checked' : '' }}>Lembur</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleFilterPanel()" class="px-4 py-2 bg-gray-300 rounded-full">Tutup</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-full">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleFilterPanel() {
    document.getElementById('filterPanel').classList.toggle('hidden');
}
</script>
@endsection
