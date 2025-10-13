@extends('layouts.karyawan')

@section('title', 'Absensi')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">

    {{-- Shift info --}}
    <div class="flex items-center justify-between mb-6">
        <div class="bg-white shadow rounded-lg p-4 flex-1 mr-6">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Mulai Shift</p>
                    <p class="font-bold text-lg">8:00 AM</p>
                </div>
                <div>
                    <p class="text-gray-500">Akhiri Shift</p>
                    <p class="font-bold text-lg">4:00 PM</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Sisa waktu shift 3 hours 34 minutes</p>
        </div>

        {{-- Tombol Clock-in/out --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-full w-40 h-40 flex items-center justify-center text-xl font-bold shadow">
            @if(!$absenHariIni)
                {{-- Belum absen hari ini → tombol Clock In --}}
                <form action="{{ route('absen.clockin') }}" method="POST" class="w-full h-full flex items-center justify-center">
                    @csrf
                    <button type="submit" class="w-full h-full rounded-full text-xl font-bold focus:outline-none">
                        Clock-in
                    </button>
                </form>
            @elseif($absenHariIni && !$absenHariIni->jam_keluar)
                {{-- Sudah absen masuk tapi belum keluar → tombol Clock Out --}}
                <form action="{{ route('absen.clockout') }}" method="POST" class="w-full h-full flex items-center justify-center">
                    @csrf
                    <button type="submit" class="w-full h-full rounded-full text-xl font-bold focus:outline-none">
                        Clock-out
                    </button>
                </form>
            @else
                {{-- Sudah absen masuk & keluar --}}
                Selesai
            @endif
        </div>
    </div>

    {{-- Aktivitas Terakhir + Map --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Aktivitas terakhir --}}
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Aktivitas Terakhir</h2>
                <a href="#" class="text-blue-600 text-sm">Lihat semua</a>
            </div>
            <ul class="space-y-3">
                <li class="flex justify-between items-center border p-2 rounded-lg">
                    <div>
                        <p class="font-medium">Clock-in</p>
                        <p class="text-sm text-gray-500">Wednesday, 10 September</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">7:47 AM</p>
                        <p class="text-green-600 text-xs">On time</p>
                    </div>
                </li>
                <li class="flex justify-between items-center border p-2 rounded-lg">
                    <div>
                        <p class="font-medium">Clock-out</p>
                        <p class="text-sm text-gray-500">Tuesday, 9 September</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">4:01 PM</p>
                        <p class="text-green-600 text-xs">On time</p>
                    </div>
                </li>
                <li class="flex justify-between items-center border p-2 rounded-lg">
                    <div>
                        <p class="font-medium">Clock-in</p>
                        <p class="text-sm text-gray-500">Tuesday, 9 September</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">8:30 AM</p>
                        <p class="text-red-500 text-xs">Late</p>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Map --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18..."
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>

</div>
@endsection
