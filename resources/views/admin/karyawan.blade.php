@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">👥 Daftar Karyawan</h1>
        <a href="{{ route('admin.karyawan.create') }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-xl shadow transition">
            + Tambah Karyawan
        </a>
    </div>

    <!-- Form Search -->
    <form method="GET" action="{{ route('admin.karyawan') }}" 
          class="flex flex-wrap gap-2 items-center bg-white dark:bg-gray-700 p-4 rounded-xl shadow">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Cari nama atau email..." 
               class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 
                      flex-1 min-w-[220px] focus:ring-2 focus:ring-blue-500 
                      dark:bg-gray-800 dark:text-gray-100">
        
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-xl shadow transition">
            Cari
        </button>

        @if(request('search'))
            <a href="{{ route('admin.karyawan') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-xl shadow transition">
                Reset
            </a>
        @endif
    </form>

    <!-- Tabel Karyawan -->
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full border-collapse bg-white dark:bg-gray-700 rounded-xl overflow-hidden">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-left">
                    <th class="py-3 px-5 font-semibold">Nama</th>
                    <th class="py-3 px-5 font-semibold">Email</th>
                    <th class="py-3 px-5 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr class="border-t border-gray-200 dark:border-gray-600 
                               hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        <td class="py-3 px-5 text-gray-800 dark:text-gray-100">{{ $employee->name }}</td>
                        <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->email }}</td>
                        <td class="py-3 px-5 text-center space-x-2">
                            <a href="{{ route('admin.karyawan.edit', $employee->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg shadow transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.karyawan.destroy', $employee->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg shadow transition"
                                        onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            Tidak ada data karyawan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $employees->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
