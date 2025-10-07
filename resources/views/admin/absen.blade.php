@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Absensi Karyawan</h1>

    @if ($absensi->isEmpty())
        <p class="text-gray-500">Belum ada data absensi.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-100">
    <tr>
        <th class="border px-4 py-2 text-left">Nama</th>
        <th class="border px-4 py-2 text-left">Tanggal</th>
        <th class="border px-4 py-2 text-left">Jam Masuk</th>
        <th class="border px-4 py-2 text-left">Jam Keluar</th>
        <th class="border px-4 py-2 text-left">Latitude</th>
        <th class="border px-4 py-2 text-left">Longitude</th>
    </tr>
</thead>
<tbody>
    @foreach ($absensi as $item)
        <tr class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ $item->user->name }}</td>
            <td class="border px-4 py-2">{{ $item->tanggal }}</td>
            <td class="border px-4 py-2">{{ $item->jam_masuk ?? '-' }}</td>
            <td class="border px-4 py-2">{{ $item->jam_keluar ?? '-' }}</td>
            <td class="border px-4 py-2">{{ $item->latitude }}</td>
            <td class="border px-4 py-2">{{ $item->longitude }}</td>
        </tr>
    @endforeach
</tbody>

            </table>
        </div>
    @endif
</div>
@endsection
