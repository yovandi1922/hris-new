@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">👥 Daftar Karyawan</h1>
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau NIP..."
                   class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button onclick="toggleFilter()" 
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg shadow transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- Kontainer Utama -->
    <div class="flex gap-4">

        <!-- Tabel Karyawan -->
        <div class="flex-1 bg-white dark:bg-gray-700 rounded-xl shadow overflow-x-auto">
            
            <!-- Tombol Tambah dan Export -->
            <div class="flex justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-xl shadow">
                    + Tambah Karyawan
                </button>
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-xl shadow">
                    Export Data
                </button>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-left">
                        <th class="py-3 px-5 font-semibold">NIP</th>
                        <th class="py-3 px-5 font-semibold">Nama Karyawan</th>
                        <th class="py-3 px-5 font-semibold">Tanggal Gabung</th>
                        <th class="py-3 px-5 font-semibold">Departemen</th>
                        <th class="py-3 px-5 font-semibold">Jabatan</th>
                        <th class="py-3 px-5 font-semibold">Email</th>
                        <th class="py-3 px-5 font-semibold text-center">Status</th>
                        <th class="py-3 px-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $employees = [
                            (object)['nip'=>'12345','nama'=>'Ahmad Fauzi','tgl_gabung'=>'2023-01-10','departemen'=>'Produksi','jabatan'=>'Staff','email'=>'ahmad@example.com','status'=>'Aktif'],
                            (object)['nip'=>'12346','nama'=>'Siti Aisyah','tgl_gabung'=>'2022-05-20','departemen'=>'HRD','jabatan'=>'Supervisor','email'=>'siti@example.com','status'=>'Aktif'],
                            (object)['nip'=>'12347','nama'=>'Budi Santoso','tgl_gabung'=>'2021-09-15','departemen'=>'Gudang','jabatan'=>'Operator','email'=>'budi@example.com','status'=>'Resign'],
                        ];
                    @endphp

                    @forelse($employees as $employee)
                        <tr class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <td class="py-3 px-5 text-gray-800 dark:text-gray-100">{{ $employee->nip }}</td>
                            <td class="py-3 px-5 text-gray-800 dark:text-gray-100">{{ $employee->nama }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->tgl_gabung }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->departemen }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->jabatan }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->email }}</td>
                            <td class="py-3 px-5 text-center text-gray-600 dark:text-gray-300">{{ $employee->status }}</td>
                            <td class="py-3 px-5 text-center space-x-2">
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg shadow transition">
                                    Edit
                                </button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg shadow transition"
                                        onclick="alert('Hapus karyawan ini?')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Tidak ada data karyawan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Sidebar Filter -->
        <div id="filterSidebar" class="w-80 bg-white dark:bg-gray-800 shadow-2xl p-5 space-y-5 rounded-xl transition-all duration-300 hidden">
            
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter</h2>
                <button onclick="toggleFilter()" 
                        class="text-gray-600 dark:text-gray-300 hover:text-red-500 text-xl font-bold">×</button>
            </div>

            <div class="space-y-5 text-sm text-gray-700 dark:text-gray-200">
                <!-- Tahun Gabung -->
                <div>
                    <h3 class="font-semibold mb-2">Tahun Gabung</h3>
                    <div class="space-y-2">
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
                        @foreach(['Semua','Aktif','Resign'] as $stat)
                            <label class="flex items-center gap-2"><input type="checkbox"> {{ $stat }}</label>
                        @endforeach
                    </div>
                </div>

                <!-- Tombol Filter -->
                <div class="flex justify-between mt-5">
                    <button onclick="toggleFilter()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">Reset</button>
                    <button onclick="toggleFilter()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Terapkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilter() {
    document.getElementById('filterSidebar').classList.toggle('hidden');
}
</script>
@endsection
