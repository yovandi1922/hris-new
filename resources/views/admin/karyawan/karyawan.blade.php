@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6 relative">

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
                    <tr onclick="openFilter()" 
                        class="cursor-pointer border-t border-gray-200 dark:border-gray-600 
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

    <!-- Filter Sidebar -->
    <div id="filterSidebar" 
         class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-gray-800 shadow-2xl transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        
        <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter</h2>
            <button onclick="closeFilter()" class="text-gray-600 dark:text-gray-300 hover:text-red-500">✕</button>
        </div>

        <div class="p-5 space-y-4 text-sm text-gray-700 dark:text-gray-200">
            
            <!-- Tahun Gabung -->
            <div>
                <h3 class="font-semibold mb-2">Tahun Gabung</h3>
                <div class="space-y-1">
                    <label class="flex items-center gap-2"><input type="checkbox"> Semua</label>
                    <label class="flex items-center gap-2"><input type="checkbox"> Pilih Tahun</label>
                    <select class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-100">
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                    </select>
                    <label class="flex items-center gap-2 mt-1"><input type="checkbox"> Rentang Tahun</label>
                    <select class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-100">
                        <option>2021–2022</option>
                        <option>2022–2023</option>
                    </select>
                </div>
            </div>

            <!-- Departemen -->
            <div>
                <h3 class="font-semibold mb-2">Departemen</h3>
                <div class="grid grid-cols-2 gap-1">
                    @foreach(['Semua','Produksi','Quality Control','Gudang','HRD','Akutansi','Design','Keamanan'] as $dept)
                        <label class="flex items-center gap-2"><input type="checkbox"> {{ $dept }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Jabatan -->
            <div>
                <h3 class="font-semibold mb-2">Jabatan</h3>
                <div class="grid grid-cols-2 gap-1">
                    @foreach(['Semua','Operator','Staff','Supervisor'] as $jab)
                        <label class="flex items-center gap-2"><input type="checkbox"> {{ $jab }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="font-semibold mb-2">Status</h3>
                <div class="grid grid-cols-2 gap-1">
                    @foreach(['Semua','Aktif','Resign','Supervisor'] as $stat)
                        <label class="flex items-center gap-2"><input type="checkbox"> {{ $stat }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between mt-5">
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">Reset</button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Script Filter -->
<script>
function openFilter() {
    document.getElementById('filterSidebar').classList.remove('translate-x-full');
}
function closeFilter() {
    document.getElementById('filterSidebar').classList.add('translate-x-full');
}
</script>
@endsection
