@extends('layouts.admin')

@section('title', 'Edit Karyawan')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Karyawan</h1>

<form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST" class="space-y-4 bg-white p-4 rounded shadow">
    @csrf
    @method('PUT')
    
    <div>
        <label class="block mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name', $karyawan->name) }}" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" class="w-full border p-2 rounded" required>
    </div>
    <div>
        <label class="block mb-1">Password (Opsional)</label>
        <input type="password" name="password" class="w-full border p-2 rounded">
        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
    </div>
    <div>
        <label class="block mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('admin.karyawan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
    </div>
</form>
@endsection
