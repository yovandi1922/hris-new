@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Manajemen Autentikasi</h1>
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Pengaturan Login Admin & User</h2>
        <p>Atur konfigurasi autentikasi di sini. (Placeholder untuk form)</p>
        <form class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="admin-email" class="block text-gray-700">Email Admin</label>
                <input type="email" id="admin-email" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="user-role" class="block text-gray-700">Role User</label>
                <select id="user-role" class="w-full p-2 border rounded">
                    <option>Admin</option>
                    <option>User</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</div>
@endsection