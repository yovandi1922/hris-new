@extends('layouts.admin')

@section('title', 'Lembur ' . $karyawan->name)

@section('content')
<h2>Lembur Karyawan: {{ $karyawan->name }}</h2>

@if($absenKaryawan->count())
<table border="1" cellpadding="10">
    <tr>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
        <th>Durasi Lembur (menit)</th>
    </tr>
    @foreach($absenKaryawan as $l)
        <tr>
            <td>{{ \Carbon\Carbon::parse($l['jam_masuk'])->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($l['jam_masuk'])->format('H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($l['jam_keluar'])->format('H:i') }}</td>
            <td>{{ $l['durasi_lembur'] }}</td>
        </tr>
    @endforeach
</table>
@else
<p>Tidak ada lembur.</p>
@endif

@endsection
