@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <header class="mb-6">
        <h1 class="text-3xl font-bold">Dashboard Admin</h1>
        <p class="text-gray-600">
            Selamat datang, kelola sistem gaji karyawan dengan mudah. 
            Tanggal: {{ date('d F Y, H:i') }}
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: Total Karyawan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Total Karyawan</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">150</p>
        </div>

        <!-- Card: Total Absensi -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Total Absensi Hari Ini</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">120</p>
        </div>

        <!-- Card: Total Pengajuan Cuti -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Pengajuan Cuti</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-2">10</p>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Terbaru</h3>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Karyawan</th>
                    <th class="p-2">Aksi</th>
                    <th class="p-2">Waktu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2">John Doe</td>
                    <td class="p-2">Clock In</td>
                    <td class="p-2">{{ date('H:i, d M Y') }}</td>
                </tr>
                <tr>
                    <td class="p-2">Jane Smith</td>
                    <td class="p-2">Pengajuan Cuti</td>
                    <td class="p-2">{{ date('H:i, d M Y', strtotime('-1 day')) }}</td>
                </tr>
            </tbody>
        </table>
        
        
    </div>
@endsection
