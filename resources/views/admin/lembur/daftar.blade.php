@extends('layouts.admin')

@section('title', 'Daftar Karyawan Lembur')

@section('content')
<h2>Daftar Karyawan</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>
   @foreach($karyawans as $karyawan)
    <tr>
        <td>{{ $karyawan->name }}</td>
        <td>{{ $karyawan->email }}</td>
        <td>
            <a href="{{ route('admin.lembur.detail', $karyawan->id) }}">Lihat Lembur</a>
        </td>
    </tr>
@endforeach

</table>
@endsection
