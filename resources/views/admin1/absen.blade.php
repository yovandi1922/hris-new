@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Data Absensi Karyawan</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        @if ($absensi->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Belum ada data absensi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Masuk</th>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Keluar</th>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Latitude</th>
                            <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Longitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->user->name }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->tanggal }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->jam_masuk ?? '-' }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->jam_keluar ?? '-' }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->latitude }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item->longitude }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Tombol Kembali -->
        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow transition">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
