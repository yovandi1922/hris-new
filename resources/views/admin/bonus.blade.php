@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Manajemen Bonus</h1>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Karyawan</th>
                <th class="px-4 py-2 border">Jumlah</th>
                <th class="px-4 py-2 border">Alasan</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bonuses as $bonus)
            <tr>
                <td class="px-4 py-2 border">{{ $bonus['id'] }}</td>
                <td class="px-4 py-2 border">{{ $bonus['employee'] }}</td>
                <td class="px-4 py-2 border">Rp {{ number_format($bonus['amount'], 0, ',', '.') }}</td>
                <td class="px-4 py-2 border">{{ $bonus['reason'] }}</td>
                <td class="px-4 py-2 border">
                    <button class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Bonus</button>
</div>
@endsection
