@extends('layouts.admin')

@section('title', 'list karyawan')

@section('content')

<h2>Daftar Karyawan</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Aksi</th>
    </tr>

    @foreach($karyawan as $k)
        <tr>
            <td>{{ $k->name }}</td>
            <td>
                <a href="{{ route('admin.pengajuan.detail', $k->id) }}">
                    Lihat Pengajuan
                </a>
            </td>
        </tr>
    @endforeach
</table>
@endsection