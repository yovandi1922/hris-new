@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Karyawan</h1>

    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                    <td class="py-2 px-4 border-b">{{ $employee->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $employee->email }}</td>
                    <td class="py-2 px-4 border-b">{{ ucfirst($employee->role) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Belum ada karyawan yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
