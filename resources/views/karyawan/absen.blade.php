@extends('layouts.karyawan')

@section('title', 'Absensi')

@section('content')
{{-- Kontainer halaman --}}
<div class="space-y-6 transition-colors duration-300">

    {{-- Shift info dan tombol absen --}}
    <div class="flex flex-wrap items-center justify-between gap-6">
        {{-- Shift info --}}
        <div class="flex-1 bg-white dark:bg-gray-800 shadow rounded-xl p-5 transition-colors duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Mulai Shift</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100">08:00</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Akhiri Shift</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100">16:00</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Sisa waktu shift: 3 jam 34 menit</p>
        </div>

        {{-- Tombol Clock-in/out --}}
        <div class="w-40 h-40 rounded-full flex items-center justify-center text-xl font-bold text-white
                    shadow-md bg-gradient-to-br from-gray-800 to-gray-700 dark:from-gray-600 dark:to-gray-500 transition-colors duration-300">
            @if(!$absenHariIni)
                <form action="{{ route('absen.clockin') }}" method="POST" class="w-full h-full flex items-center justify-center">
                    @csrf
                    <button type="submit" class="w-full h-full rounded-full">Clock-in</button>
                </form>
            @elseif($absenHariIni && !$absenHariIni->jam_keluar)
                <form action="{{ route('absen.clockout') }}" method="POST" class="w-full h-full flex items-center justify-center">
                    @csrf
                    <button type="submit" class="w-full h-full rounded-full">Clock-out</button>
                </form>
            @else
                <span class="text-sm">Selesai</span>
            @endif
        </div>
    </div>

    {{-- Aktivitas Terakhir + Map --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Aktivitas terakhir --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 transition-colors duration-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Aktivitas Terakhir</h2>
                <a href="#" class="text-blue-600 dark:text-blue-400 text-sm">Lihat semua</a>
            </div>
            <ul class="space-y-3">
                <li class="flex justify-between items-center border p-3 rounded-lg border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-100">Clock-in</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Rabu, 10 September</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 dark:text-gray-100">07:47</p>
                        <p class="text-green-600 dark:text-green-400 text-xs">Tepat waktu</p>
                    </div>
                </li>
                <li class="flex justify-between items-center border p-3 rounded-lg border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-100">Clock-out</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Selasa, 9 September</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 dark:text-gray-100">16:01</p>
                        <p class="text-green-600 dark:text-green-400 text-xs">Tepat waktu</p>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Map --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden transition-colors duration-300">
            <div class="w-full h-72 bg-gray-50 dark:bg-gray-900">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18..."
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection
