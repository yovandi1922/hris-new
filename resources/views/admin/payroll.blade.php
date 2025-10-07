@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Manajemen Gaji</h1>

    <!-- Bagian Pemasukan -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Pemasukan</h2>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($salaryComponents['income'] ?? []) as $comp)
                    <tr>
                        <td class="border px-4 py-2">{{ $comp['id'] }}</td>
                        <td class="border px-4 py-2">{{ $comp['name'] }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">
                            <button class="text-blue-500">Edit</button>
                            <button class="text-red-500">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-2">Belum ada data pemasukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Pemasukan</button>
    </div>

    <!-- Bagian Potongan -->
    <div>
        <h2 class="text-2xl font-semibold mb-4">Potongan</h2>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($salaryComponents['deductions'] ?? []) as $comp)
                    <tr>
                        <td class="border px-4 py-2">{{ $comp['id'] }}</td>
                        <td class="border px-4 py-2">{{ $comp['name'] }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($comp['amount'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">
                            <button class="text-blue-500">Edit</button>
                            <button class="text-red-500">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-2">Belum ada data potongan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Tambah Potongan</button>
    </div>
</div>
@endsection
