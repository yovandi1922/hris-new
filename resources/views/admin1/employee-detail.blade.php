@extends('admin.dashboard')

@section('content')
<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6">Detail Karyawan - {{ $employee['nama'] }}</h1>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center md:col-span-1">
                <img src="{{ $employee['foto'] }}" alt="Foto {{ $employee['nama'] }}" class="w-40 h-40 rounded-full mx-auto mb-4 border-2 border-gray-300">
                <h2 class="text-2xl font-semibold">{{ $employee['nama'] }}</h2>
                <p class="text-gray-600">{{ $employee['jabatan'] }} - {{ $employee['departemen'] }}</p>
                <p class="text-gray-500">Status: {{ $employee['status'] }}</p>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-xl font-semibold mb-4">Informasi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="font-bold">Alamat:</label> {{ $employee['alamat'] }}</div>
                    <div><label class="font-bold">Jenis Kelamin:</label> {{ $employee['jenis_kelamin'] }}</div>
                    <div><label class="font-bold">Tanggal Lahir:</label> {{ date('d F Y', strtotime($employee['tanggal_lahir'])) }}</div>
                    <div><label class="font-bold">Tanggal Masuk:</label> {{ date('d F Y', strtotime($employee['tanggal_masuk'])) }}</div>
                    <div><label class="font-bold">Nomor HP:</label> {{ $employee['nomor_hp'] }}</div>
                    <div><label class="font-bold">Email:</label> {{ $employee['email'] }}</div>
                    <div><label class="font-bold">Jabatan/Role:</label> {{ $employee['jabatan'] }} / {{ $employee['role'] }}</div>
                    <div><label class="font-bold">Gaji Pokok:</label> Rp {{ number_format($employee['gaji_pokok'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('admin.core-hr') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            <a href="{{ route('admin.employee.detail', ['id' => $employee['id']]) }}">
    Lihat Detail
</a>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">Edit</button>
            <button class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
        </div>
    </div>
</div>
@endsection