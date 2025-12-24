@extends('layouts.karyawan')

@section('title', 'Absensi')

@section('content')
<div class="transition-colors duration-300">

    <div class="flex flex-wrap items-start gap-6">

        {{-- Kolom Kiri - Info Shift & Aktivitas --}}
        <div class="flex flex-col w-[650px]">
            {{-- Info Shift --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-t-xl p-5 transition-colors duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-play text-black dark:text-white"></i> Mulai Shift
                        </p>
                        <p class="font-bold text-lg text-gray-900 dark:text-gray-100">08:00</p>
                    </div>

                    <div class="w-px h-10 bg-gray-300 dark:bg-gray-700"></div>

                    <div>
                        <p class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-flag-checkered text-black dark:text-white"></i> Akhiri Shift
                        </p>
                        <p class="font-bold text-lg text-gray-900 dark:text-gray-100">16:00</p>
                    </div>
                </div>
            </div>

            {{-- Waktu Sisa --}}
            <div class="bg-white dark:bg-gray-700 shadow rounded-b-xl p-3 border-t border-gray-300 dark:border-gray-600 transition-colors duration-300">
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                    @if($absenHariIni && $absenHariIni->jam_keluar)
                        @php
                            $sisa = 16 - \Carbon\Carbon::parse($absenHariIni->jam_masuk)->format('H');
                        @endphp
                        Sisa waktu shift: {{ $sisa }} jam
                    @else
                        Sisa waktu shift: -
                    @endif
                </p>
            </div>
{{-- Aktivitas Hari Ini --}}
<h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4">Aktivitas Hari Ini</h2>
<div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 mt-2 transition-colors duration-300">
    <ul class="space-y-3">

        {{-- CLOCK IN --}}
        <li class="flex justify-between items-center border p-3 rounded-lg border-gray-200 dark:border-gray-700">
            @php
                $jamMasuk = $absenHariIni?->jam_masuk ? \Carbon\Carbon::parse($absenHariIni->jam_masuk) : null;
                $ketMasuk = '-';
                $warnaMasuk = 'text-gray-500';

                if ($jamMasuk) {
                    if ($jamMasuk->format('H:i') === '08:00') {
                        $ketMasuk = 'Tepat Waktu';
                        $warnaMasuk = 'text-green-500';
                    } elseif ($jamMasuk->greaterThan(\Carbon\Carbon::parse('08:00'))) {
                        $ketMasuk = 'Terlambat';
                        $warnaMasuk = 'text-red-500';
                    }
                }
            @endphp

            <span class="font-medium text-gray-800 dark:text-gray-100">Clock-in</span>

            <span class="font-bold text-gray-800 dark:text-gray-100">
                {{ $jamMasuk ? $jamMasuk->format('H:i') : '-' }}
                <span class="ml-2 {{ $warnaMasuk }} text-sm">({{ $ketMasuk }})</span>
            </span>
        </li>

        {{-- CLOCK OUT --}}
        <li class="flex justify-between items-center border p-3 rounded-lg border-gray-200 dark:border-gray-700">
            @php
                $jamKeluar = $absenHariIni?->jam_keluar ? \Carbon\Carbon::parse($absenHariIni->jam_keluar) : null;
                $ketKeluar = '-';
                $warnaKeluar = 'text-gray-500';

                if ($jamKeluar) {
                    $jamBatasL = $jamKeluar->copy()->setTime(16,0,0);
                    if ($jamKeluar->lte($jamBatasL)) {
                        $ketKeluar = 'Selesai';
                        $warnaKeluar = 'text-green-500';
                    } else {
                        $lemburMenit = $jamKeluar->diffInMinutes($jamBatasL);
                        $lemburJam = floor($lemburMenit / 60);
                        if ($lemburJam >= 1) {
                            $ketKeluar = 'Lembur ' . $lemburJam . ' jam';
                            $warnaKeluar = 'text-yellow-500';
                        } else {
                            $ketKeluar = 'Selesai';
                            $warnaKeluar = 'text-green-500';
                        }
                    }
                }
            @endphp

                <span class="font-medium text-gray-800 dark:text-gray-100">Clock-out</span>

                <span class="font-bold text-gray-800 dark:text-gray-100">
                    {{ $jamKeluar ? $jamKeluar->format('H:i') : '-' }}
                    <span class="ml-2 {{ $warnaKeluar }} text-sm">({{ $ketKeluar }})
                        @if($absenHariIni && $absenHariIni->jam_keluar)
                            @php
                                $jamKeluarL = \Carbon\Carbon::parse($absenHariIni->jam_keluar);
                                $jamBatasL = $jamKeluarL->copy()->setTime(16,0,0);
                                $lemburMenit = $jamKeluarL->gt($jamBatasL) ? $jamBatasL->diffInMinutes($jamKeluarL) : 0;
                                $lemburJam = floor($lemburMenit / 60);
                            @endphp
                            {{-- DEBUG OUTPUT --}}
                            @if($lemburJam >= 1)
                                &nbsp;|&nbsp;<span class="text-yellow-500">Lembur {{ $lemburJam }} jam</span>
                            @endif
                        @endif
                    </span>
                </span>
        </li>

    </ul>
</div>


           {{-- Aktivitas Sebelumnya --}}
<h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4">Aktivitas Sebelumnya</h2>
<div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 mt-2 transition-colors duration-300">

    @if($absenKemarin)

        @php
            // JAM MASUK
            $jamMasukKemarin = \Carbon\Carbon::parse($absenKemarin->jam_masuk);
            if ($jamMasukKemarin->format('H:i') === '08:00') {
                $ketMasukK = 'Tepat Waktu';
                $warnaMasukK = 'text-green-500';
            } elseif ($jamMasukKemarin->greaterThan(\Carbon\Carbon::parse('08:00'))) {
                $ketMasukK = 'Terlambat';
                $warnaMasukK = 'text-red-500';
            } else {
                $ketMasukK = '-';
                $warnaMasukK = 'text-gray-500';
            }

            // JAM KELUAR
            $jamKeluarKemarin = \Carbon\Carbon::parse($absenKemarin->jam_keluar);
            $jamBatasKemarin = $jamKeluarKemarin->copy()->setTime(16,0,0);
            if ($jamKeluarKemarin->lte($jamBatasKemarin)) {
                $ketKeluarK = 'Selesai';
                $warnaKeluarK = 'text-green-500';
            } else {
                $selisihMenit = $jamKeluarKemarin->diffInMinutes($jamBatasKemarin);
                $lemburJamK = floor($selisihMenit / 60);
                if ($lemburJamK >= 1) {
                    $ketKeluarK = 'Lembur ' . $lemburJamK . ' jam';
                    $warnaKeluarK = 'text-yellow-500';
                } else {
                    $ketKeluarK = 'Selesai';
                    $warnaKeluarK = 'text-green-500';
                }
            }
        @endphp

        <ul class="space-y-2">

            {{-- CLOCK IN --}}
            <li class="flex justify-between border p-3 rounded-lg border-gray-200 dark:border-gray-700">
                <span class="font-medium text-gray-800 dark:text-gray-100">Clock-in</span>

                <span class="font-bold text-gray-800 dark:text-gray-100">
                    {{ $jamMasukKemarin->format('H:i') }}
                    <span class="ml-2 {{ $warnaMasukK }} text-sm">({{ $ketMasukK }})</span>
                </span>
            </li>

            {{-- CLOCK OUT --}}
            <li class="flex justify-between border p-3 rounded-lg border-gray-200 dark:border-gray-700">
                <span class="font-medium text-gray-800 dark:text-gray-100">Clock-out</span>

                <span class="font-bold text-gray-800 dark:text-gray-100">
                    {{ $jamKeluarKemarin->format('H:i') }}
                    <span class="ml-2 {{ $warnaKeluarK }} text-sm">({{ $ketKeluarK }})</span>
                </span>
            </li>
        </ul>

    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data aktivitas sebelumnya.</p>
    @endif
</div>

        </div>
        {{-- Kolom Kanan - Tombol Absen & Map --}}
        <div class="flex flex-col items-center space-y-4">

            {{-- Tombol Absen --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 flex items-center justify-center w-[260px] h-95 transition-colors duration-300">
                <div class="w-40 h-40 rounded-full flex flex-col items-center justify-center text-xl font-bold text-gray-800 dark:text-gray-100
                            border-[10px] border-gray-400 shadow-md bg-transparent">

                    {{-- Clock In --}}
@if(!$absenHariIni)
    <form id="formClockIn" action="{{ route('absen.clockin') }}" method="POST" class="flex flex-col items-center space-y-2">
        @csrf
        <button type="submit" class="flex flex-col items-center">
            <i class="fa-solid fa-bell text-2xl"></i>
            <span class="text-base font-semibold">Clock-in</span>
        </button>
    </form>

{{-- Clock Out --}}
@elseif($absenHariIni && !$absenHariIni->jam_keluar)
    <form id="formClockOut" action="{{ route('absen.clockout') }}" method="POST" class="flex flex-col items-center space-y-2">
        @csrf
        <button type="submit" class="flex flex-col items-center">
            <i class="fa-solid fa-bell text-2xl"></i>
            <span class="text-base font-semibold">Clock-out</span>
        </button>
    </form>
@else
    <span class="text-sm">Selesai</span>
@endif

                </div>
            </div>

            {{-- Map Lokasi Kantor --}}
            <iframe
                id="mapKantor"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7020.54201692134!2d110.75016364303524!3d-7.537057736596229!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a151693970459%3A0x393e41c5416cbc38!2sNeo%20Sangkal%20Putung!5e0!3m2!1sid!2sid!4v1761657936960!5m2!1sid!2sid"
                class="w-[260px] h-[260px] rounded-xl"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>

        </div>
    </div>
</div>

{{-- Script Geolocation --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formClockIn = document.getElementById('formClockIn');
    const formClockOut = document.getElementById('formClockOut');

    // Nonaktif sementara sebelum verifikasi lokasi
    if (formClockIn) formClockIn.querySelector('span').innerText = 'Memeriksa lokasi...';
    if (formClockOut) formClockOut.querySelector('span').innerText = 'Memeriksa lokasi...';

    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung GPS lokasi.');
        return;
    }

    navigator.geolocation.getCurrentPosition(function(position) {
        const userLat = position.coords.latitude;
        const userLon = position.coords.longitude;

        // 🏢 Koordinat kantor
        const kantorLat = -7.540880;
        const kantorLon = 110.743645;

        // 📏 Radius dalam kilometer (0.1 = 100 meter)
        const radius = 10;

        const jarak = hitungJarak(userLat, userLon, kantorLat, kantorLon);
        console.log('Jarak ke kantor:', jarak, 'km');

        if (jarak > radius) {
            alert('Anda berada di luar area kantor (' + Math.round(jarak*1000) + ' m). Absen tidak bisa dilakukan.');

            // ❌ Blok form agar tidak bisa submit
            if (formClockIn) formClockIn.addEventListener('submit', e => e.preventDefault());
            if (formClockOut) formClockOut.addEventListener('submit', e => e.preventDefault());
        } else {
            console.log('✅ Dalam area kantor, absen diaktifkan.');
        }

        // ✅ Ubah teks tombol kembali ke normal
        if (formClockIn) formClockIn.querySelector('span').innerText = 'Clock-in';
        if (formClockOut) formClockOut.querySelector('span').innerText = 'Clock-out';

        // 🗺️ Update map ke posisi user
        const mapIframe = document.getElementById('mapKantor');
        mapIframe.src = `https://www.google.com/maps?q=${userLat},${userLon}&z=16&output=embed`;
    }, function(error) {
        alert('Gagal mengakses lokasi: ' + error.message);
        if (formClockIn) formClockIn.querySelector('span').innerText = 'Gagal akses GPS';
        if (formClockOut) formClockOut.querySelector('span').innerText = 'Gagal akses GPS';
    });
});

function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371; // km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 +
              Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
              Math.sin(dLon/2)**2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}
</script>



@endsection
