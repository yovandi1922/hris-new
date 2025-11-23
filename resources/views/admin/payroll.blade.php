@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Manajemen Gaji</h1>
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Pemasukan</h2>
        <table class="min-w-full bg-white border">
            <thead><tr><th>ID</th><th>Nama</th><th>Jumlah</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($salaryComponents['income'] as $comp)
                <tr><td>{{ $comp['id'] }}</td><td>{{ $comp['name'] }}</td><td>Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td><td><button>Edit</button> <button>Delete</button></td></tr>
                @endforeach
            </tbody>
        </table>
        <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Pemasukan</button>
    </div>
    <div>
        <h2 class="text-2xl font-semibold mb-4">Potongan</h2>
        <table class="min-w-full bg-white border">
            <thead><tr><th>ID</th><th>Nama</th><th>Jumlah</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($salaryComponents['deductions'] as $comp)
                <tr><td>{{ $comp['id'] }}</td><td>{{ $comp['name'] }}</td><td>Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td><td><button>Edit</button> <button>Delete</button></td></tr>
                @endforeach
            </tbody>
        </table>
        <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Potongan</button>
    </div>
</div>
@endsection