@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold mb-1 dark:text-white font-sans">Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>

    {{-- Grid layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Shift Hari Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 flex flex-col justify-center text-center transition">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Shift Hari Ini</h3>

            <div class="flex justify-center items-center space-x-3">
                {{-- Jam masuk --}}
                <div class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    08:00
                </div>

                {{-- separator --}}
                <div class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    –
                </div>

                {{-- Jam keluar --}}
                <div class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    16:00
                </div>
            </div>
        </div>

        {{-- Jam Sekarang --}}
        <div id="clock-card" class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-6 text-center transition">
            <div class="text-6xl font-bold text-gray-900 dark:text-gray-100" id="clock-time">09:14</div>
            <p class="text-lg text-gray-500 dark:text-gray-400 mt-3" id="clock-date">Rabu, 10 September 2025</p>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-8 transition">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Absensi Hari Ini</h3>

            <div class="space-y-6">
                {{-- Clock-in --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-800 dark:text-gray-100">Clock-in</p>
                        <p class="text-sm text-green-500">Tepat Waktu</p>
                    </div>
                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">07:47 WIB</p>
                </div>

                {{-- Clock-out --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-800 dark:text-gray-100">Clock-out</p>
                        <p class="text-sm text-gray-500">Belum Absen</p>
                    </div>
                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">--:-- WIB</p>
                </div>
            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('karyawan.absen') }}" class="px-4 py-2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg text-sm font-semibold hover:opacity-90 transition">
                    Lihat Detail
                </a>
            </div>
        </div>

        {{-- Gaji Bulan Ini --}}
        <div class="bg-white dark:bg-gray-950 shadow-md rounded-2xl p-7 flex flex-col justify-between transition">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Gaji Bulan Ini</h3>
                <p class="text-4xl font-bold text-gray-900 dark:text-gray-100">Rp 4.500.000</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Sudah ditransfer (2 Oktober 2025)</p>
            </div>
            <a href="#" class="mt-6 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold text-center hover:opacity-90 transition">
                Lihat Slip Gaji
            </a>
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
