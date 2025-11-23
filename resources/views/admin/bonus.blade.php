@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Manajemen Bonus</h1>
    <table class="min-w-full bg-white border">
        <thead><tr><th>ID</th><th>Karyawan</th><th>Jumlah</th><th>Alasan</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($bonuses as $bonus)
            <tr><td>{{ $bonus['id'] }}</td><td>{{ $bonus['employee'] }}</td><td>Rp {{ number_format($bonus['amount'], 0, ',', '.') }}</td><td>{{ $bonus['reason'] }}</td><td><button>Edit</button> <button>Delete</button></td></tr>
            @endforeach
        </tbody>
    </table>
    <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Bonus</button>
</div>
@endsection