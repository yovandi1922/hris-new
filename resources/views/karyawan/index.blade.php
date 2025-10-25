@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <p class="text-gray-500 dark:text-gray-400">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>

    {{-- Grid utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Shift Hari Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 flex flex-col justify-center text-center transition">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Shift Hari Ini</h3>
            <div class="flex justify-center items-center space-x-4 text-2xl font-bold text-gray-900 dark:text-gray-100">
                <span>08:00</span>
                <span>–</span>
                <span>16:00</span>
            </div>
        </div>

        {{-- Jam Sekarang --}}
        <div id="clock-card" class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 text-center transition">
            <div class="text-5xl font-bold text-gray-900 dark:text-gray-100" id="clock-time">09:14</div>
            <p class="text-gray-500 dark:text-gray-400 mt-2" id="clock-date">Rabu, 10 September 2025</p>
        </div>

        {{-- Gaji Bulan Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 flex flex-col justify-between transition">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Gaji Bulan Ini</h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">Rp 4.500.000</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sudah ditransfer (2 Oktober 2025)</p>
            </div>
            <a href="#" class="mt-4 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold text-center hover:opacity-90 transition">
                Lihat Slip Gaji
            </a>
        </div>

    </div>

    {{-- Baris kedua --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Absensi Hari Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 transition">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Absensi Hari Ini</h3>

            <div class="space-y-4">
                {{-- Clock-in --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-100">Clock-in</p>
                        <p class="text-sm text-green-500">Tepat Waktu</p>
                    </div>
                    <p class="font-semibold text-gray-700 dark:text-gray-200">07:47 WIB</p>
                </div>

                {{-- Clock-out --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-100">Clock-out</p>
                        <p class="text-sm text-gray-500">Belum Absen</p>
                    </div>
                    <p class="font-semibold text-gray-700 dark:text-gray-200">--:-- WIB</p>
                </div>
            </div>

            <div class="mt-5 text-right">
                <a href="{{ route('karyawan.absen') }}" class="px-4 py-2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                    Lihat Detail
                </a>
            </div>
        </div>

        {{-- Placeholder tambahan --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 flex items-center justify-center text-gray-400 dark:text-gray-500 text-sm">
            <p>Konten tambahan bisa ditempatkan di sini (misalnya grafik kehadiran, ringkasan cuti, dsb).</p>
        </div>
    </div>
</div>

{{-- Jam real-time --}}
<script>
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const date = now.toLocaleDateString('id-ID', options);
        document.getElementById('clock-time').textContent = time;
        document.getElementById('clock-date').textContent = date.charAt(0).toUpperCase() + date.slice(1);
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection
