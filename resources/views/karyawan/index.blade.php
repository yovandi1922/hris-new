@extends('layouts.karyawan')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Halo, {{ auth()->user()->name }}</h2>
    <p class="text-gray-600">Selamat datang di dashboard karyawan.</p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-100 p-4 rounded shadow">
            <h3 class="text-lg font-bold">Absen Hari Ini</h3>
            <p class="text-gray-700">Klik tombol absen untuk mencatat kehadiran.</p>
            <a href="{{ route('karyawan.absen') }}" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Absen Sekarang
            </a>
             <a href="{{ route('karyawan.pengajuan') }}" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                pengajuan
            </a>
        </div>

        <div class="bg-green-100 p-4 rounded shadow">
            <h3 class="text-lg font-bold">Profil</h3>
            <p class="text-gray-700">Periksa informasi profil dan data karyawan Anda.</p>
        </div>
    </div>
</div>
@endsection
