@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<h1 class="text-2xl font-bold mb-4">Daftar Karyawan</h1>

<!-- Form Search -->
<form method="GET" action="{{ route('admin.karyawan') }}" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" 
           placeholder="Cari nama atau email..." 
           class="border p-2 rounded w-1/3">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
    @if(request('search'))
        <a href="{{ route('admin.karyawan') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Reset</a>
    @endif
</form>

<!-- Tabel Karyawan -->
<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200">
            <th class="py-2 px-4">Nama</th>
            <th class="py-2 px-4">Email</th>
            <th class="py-2 px-4 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employees as $employee)
            <tr class="border-t">
                <td class="py-2 px-4">{{ $employee->name }}</td>
                <td class="py-2 px-4">{{ $employee->email }}</td>
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('admin.karyawan.edit', $employee->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('admin.karyawan.destroy', $employee->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded"
                                onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data karyawan.</td>
            </tr>
        @endforelse
    </tbody>
</table>


<!-- Pagination -->
<div class="mt-4">
    {{ $employees->appends(['search' => request('search')])->links() }}
</div>

<a href="{{ route('admin.karyawan.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">
    Tambah Karyawan
</a>
@endsection
