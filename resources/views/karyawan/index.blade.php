@extends('layouts.karyawan')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Header --}}
    <header class="bg-gradient-to-r from-blue-700 to-blue-500 text-white shadow-md py-6 px-8 rounded-b-3xl">
        <h2 class="text-3xl font-bold">Halo, {{ auth()->user()->name }} 👋</h2>
        <p class="text-blue-100 mt-1">Selamat datang di dashboard karyawan.</p>
    </header>

    {{-- Konten utama --}}
    <main class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card Absen --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-xl transition">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Absen Hari Ini</h3>
                <p class="text-gray-500 text-sm">Klik tombol di bawah untuk mencatat kehadiran Anda.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('karyawan.absen') }}" class="block w-full bg-gradient-to-r from-gray-900 to-gray-700 text-white py-3 rounded-xl text-center font-semibold hover:opacity-90 transition">
                    Absen Sekarang
                </a>
            </div>
        </div>

        {{-- Card Pengajuan --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-xl transition">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pengajuan</h3>
                <p class="text-gray-500 text-sm">Ajukan cuti atau izin dengan cepat di sini.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('karyawan.pengajuan') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white py-3 rounded-xl text-center font-semibold hover:opacity-90 transition">
                    Ajukan Sekarang
                </a>
            </div>
        </div>

        {{-- Card Profil --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between hover:shadow-xl transition">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Profil Karyawan</h3>
                <p class="text-gray-500 text-sm">Lihat dan perbarui informasi pribadi Anda.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('karyawan.data') }}" class="block w-full bg-gradient-to-r from-green-600 to-green-400 text-white py-3 rounded-xl text-center font-semibold hover:opacity-90 transition">
                    Lihat Profil
                </a>
            </div>
        </div>

    </main>

    {{-- Aktivitas Terakhir --}}
    <section class="px-6 pb-10">
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terakhir</h3>
                <a href="#" class="text-blue-600 text-sm hover:underline">Lihat semua</a>
            </div>

            <ul class="divide-y divide-gray-200">
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-700">Clock-in</p>
                        <p class="text-sm text-gray-500">Jumat, 18 Oktober</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">08:01 AM</p>
                        <p class="text-green-600 text-xs font-medium">Tepat waktu</p>
                    </div>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-700">Clock-out</p>
                        <p class="text-sm text-gray-500">Kamis, 17 Oktober</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">16:00 PM</p>
                        <p class="text-green-600 text-xs font-medium">Tepat waktu</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>

</div>
@endsection
