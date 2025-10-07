@extends('layouts.karyawan')

@section('title', 'Absen Kehadiran')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-md mx-auto text-center">
    <h1 class="text-2xl font-bold mb-4">Absen Kehadiran</h1>
    <p class="text-gray-600 mb-4">
        @if (!$absenHariIni)
            Klik tombol di bawah untuk absen masuk.
        @elseif($absenHariIni && !$absenHariIni->jam_keluar)
            Anda sudah absen masuk, klik tombol untuk absen keluar.
        @else
            Anda sudah absen masuk & keluar hari ini.
        @endif
    </p>

    @if (!$absenHariIni || ($absenHariIni && !$absenHariIni->jam_keluar))
        <form action="{{ route('karyawan.absen.store') }}" method="POST" id="absensiForm">
            @csrf
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <button 
                type="button"
                id="btnAbsen"
                class="
                    @if (!$absenHariIni)
                        bg-blue-600 hover:bg-blue-700
                    @else
                        bg-red-600 hover:bg-red-700
                    @endif
                    text-white font-semibold px-6 py-3 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed
                ">
                @if (!$absenHariIni)
                    Absen Masuk
                @else
                    Absen Keluar
                @endif
            </button>
        </form>
    @else
        <p class="text-green-600 font-semibold">✅ Anda sudah menyelesaikan absensi hari ini.</p>
    @endif

    <p id="status" class="text-sm text-gray-500 mt-3"></p>
</div>

<script>
document.getElementById('btnAbsen')?.addEventListener('click', function () {
    if (navigator.geolocation) {
        document.getElementById('status').innerText = "Mendeteksi lokasi...";
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const kantorLat = -7.566381;
            const kantorLng = 110.798996;
            const radius = 10000; 

            const jarak = getDistanceFromLatLonInM(lat, lng, kantorLat, kantorLng);

            if (jarak <= radius) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('absensiForm').submit();
            } else {
                document.getElementById('status').innerText = 
                    "Kamu berada di luar radius kantor ("+ Math.round(jarak) +" m)";
            }
        }, function () {
            document.getElementById('status').innerText = "Gagal mendeteksi lokasi. Aktifkan GPS.";
        });
    } else {
        document.getElementById('status').innerText = "Browser tidak mendukung geolocation.";
    }
});

function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
    const R = 6371e3;
    const dLat = (lat2-lat1) * Math.PI/180;
    const dLon = (lon2-lon1) * Math.PI/180;
    const a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
</script>
@endsection
