@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <p class="text-gray-300">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>

    {{-- Layout utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Shift Hari Ini --}}
        <div class="bg-gray-900 shadow-lg rounded-3xl p-8 flex flex-col text-center">
            <h3 class="text-lg font-semibold text-gray-300 mb-3">Shift Hari Ini</h3>

            <div class="flex justify-center items-center space-x-4 text-4xl font-bold text-gray-100">
                <span>08:00</span>
                <span>–</span>
                <span>16:00</span>
            </div>
        </div>

        {{-- Jam sekarang --}}
        <div class="bg-gray-900 shadow-lg rounded-3xl p-8 text-center">
            <div id="clock-time" class="text-7xl font-bold text-gray-100">09:14</div>
            <p id="clock-date" class="text-lg text-gray-400 mt-3">Rabu, 10 September 2025</p>
        </div>

        {{-- Gaji Bulan Ini --}}
        <div class="bg-gray-900 shadow-lg rounded-3xl p-8 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-300 mb-3">Gaji Bulan Ini</h3>
                <p class="text-4xl font-bold text-gray-100">Rp 4.500.000</p>
                <p class="text-gray-400 text-sm mt-2">Sudah ditransfer (2 Oktober 2025)</p>
            </div>

            <a href="#"
                class="mt-6 bg-white/10 text-white px-5 py-2 rounded-xl font-semibold text-center hover:bg-white/20 transition">
                Lihat Slip Gaji
            </a>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="bg-gray-900 shadow-lg rounded-3xl p-8">
            <h3 class="text-lg font-semibold text-gray-300 mb-6">Absensi Hari Ini</h3>

            <div class="space-y-6">

                {{-- Clock-in --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-100">Clock-in</p>

                        @php
                            $status = 'Belum Absen';
                            $color  = 'text-gray-500';

                            if ($absenHariIni && $absenHariIni->jam_masuk) {
                                $jamMasuk = \Carbon\Carbon::parse($absenHariIni->jam_masuk);

                                if ($jamMasuk->lte(\Carbon\Carbon::createFromTime(8,0,0))) {
                                    $status = 'Tepat Waktu';
                                    $color  = 'text-green-400';
                                } else {
                                    $status = 'Terlambat';
                                    $color  = 'text-red-400';
                                }
                            }
                        @endphp

                        <p class="text-sm {{ $color }}">{{ $status }}</p>
                    </div>

                    <p class="font-semibold text-gray-100">
                        {{ $absenHariIni && $absenHariIni->jam_masuk 
                            ? \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H:i')
                            : '--:--' }} WIB
                    </p>
                </div>

                {{-- Clock-out --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-100">Clock-out</p>

                        @php
                            $statusKeluar = 'Belum Absen';
                            $colorKeluar  = 'text-gray-500';
                            $jamLembur = 0;

                            if ($absenHariIni && $absenHariIni->jam_keluar) {
                                $jamKeluar = \Carbon\Carbon::parse($absenHariIni->jam_keluar);
                                $batas = \Carbon\Carbon::createFromTime(16,0,0);

                                if ($jamKeluar->lte($batas)) {
                                    $statusKeluar = 'Pulang Tepat Waktu';
                                    $colorKeluar  = 'text-green-400';
                                } else {
                                    $jamLembur = $jamKeluar->diffInHours($batas);
                                    $statusKeluar = "Lembur $jamLembur Jam";
                                    $colorKeluar  = 'text-blue-400';
                                }
                            }
                        @endphp

                        <p class="text-sm {{ $colorKeluar }}">{{ $statusKeluar }}</p>
                    </div>

                    <p class="font-semibold text-gray-100">
                        {{ $absenHariIni && $absenHariIni->jam_keluar 
                            ? \Carbon\Carbon::parse($absenHariIni->jam_keluar)->format('H:i')
                            : '--:--' }} WIB
                    </p>
                </div>

            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('karyawan.absen') }}"
                    class="bg-white/10 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-white/20 transition">
                    Lihat Detail
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Script Jam Digital --}}
<script>
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        const date = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('clock-time').textContent = time;
        document.getElementById('clock-date').textContent =
            date.charAt(0).toUpperCase() + date.slice(1);
    }

    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection
