@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Karyawan</h1>

<form action="{{ route('admin.karyawan.store') }}" method="POST" class="space-y-4 bg-white p-4 rounded shadow">
    @csrf
    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="name" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1">Email</label>
        <input type="email" name="email" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
