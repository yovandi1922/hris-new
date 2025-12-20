@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6 p-6 bg-gray-100 dark:bg-[#020617] min-h-screen transition-colors">

    <!-- ================= HEADER ================= -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            👥 Daftar Karyawan
        </h1>

        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau NIP..."
                   class="w-64 px-4 py-2 rounded-xl
                          border border-gray-300 dark:border-gray-700
                          bg-white dark:bg-[#111827]
                          text-gray-800 dark:text-gray-100
                          focus:ring-2 focus:ring-yellow-400 focus:outline-none">

            <button onclick="toggleFilter()" 
                    class="px-4 py-2 rounded-xl shadow
                           bg-gray-200 hover:bg-gray-300
                           dark:bg-[#1f2937] dark:hover:bg-[#374151]
                           text-gray-700 dark:text-gray-100 transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="flex gap-6">

        <!-- ================= TABEL ================= -->
        <div class="flex-1 bg-white dark:bg-[#020617] rounded-3xl shadow-md border border-gray-200 dark:border-gray-800 overflow-x-auto">

            <!-- Toolbar -->
            <div class="flex justify-between p-5 border-b border-gray-200 dark:border-gray-800">
                <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 rounded-xl shadow transition">
                    + Tambah Karyawan
                </button>
                <button class="bg-slate-200 hover:bg-slate-300 dark:bg-[#1f2937] dark:hover:bg-[#374151]
                               text-gray-800 dark:text-gray-100 px-5 py-2 rounded-xl shadow transition">
                    Export Data
                </button>
            </div>

            <!-- Table -->
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-[#111827]
                               text-gray-700 dark:text-gray-200 text-left">
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
                        <tr class="border-t border-gray-200 dark:border-gray-800
                                   hover:bg-gray-50 dark:hover:bg-[#111827] transition">
                            <td class="py-3 px-5 text-gray-800 dark:text-gray-100">{{ $employee->nip }}</td>
                            <td class="py-3 px-5 text-gray-800 dark:text-gray-100">{{ $employee->nama }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->tgl_gabung }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->departemen }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->jabatan }}</td>
                            <td class="py-3 px-5 text-gray-600 dark:text-gray-300">{{ $employee->email }}</td>
                            <td class="py-3 px-5 text-center">
                                <span class="px-3 py-1 text-xs rounded-full
                                    {{ $employee->status == 'Aktif'
                                        ? 'bg-emerald-100 text-emerald-600'
                                        : 'bg-red-100 text-red-600' }}">
                                    {{ $employee->status }}
                                </span>
                            </td>
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

        <!-- ================= FILTER SIDEBAR ================= -->
        <div id="filterSidebar"
             class="w-80 bg-white dark:bg-[#020617]
                    border border-gray-200 dark:border-gray-800
                    rounded-3xl shadow-2xl p-6 space-y-6
                    hidden transition-all duration-300">

            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filter</h2>
                <button onclick="toggleFilter()" class="text-xl text-gray-500 hover:text-red-500">×</button>
            </div>

            <div class="space-y-5 text-sm text-gray-700 dark:text-gray-200">

                <!-- Tahun Gabung -->
                <div>
                    <h3 class="font-semibold mb-2">Tahun Gabung</h3>
                    <select class="w-full rounded-xl p-2
                                   bg-gray-100 dark:bg-[#111827]
                                   border border-gray-300 dark:border-gray-700">
                        <option>Semua</option>
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                    </select>
                </div>

                <!-- Departemen -->
                <div>
                    <h3 class="font-semibold mb-2">Departemen</h3>
                    <div class="grid grid-cols-2 gap-1">
                        @foreach(['Produksi','QC','Gudang','HRD','Akuntansi','Design','Keamanan'] as $dept)
                            <label class="flex items-center gap-2">
                                <input type="checkbox"> {{ $dept }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Jabatan -->
                <div>
                    <h3 class="font-semibold mb-2">Jabatan</h3>
                    <div class="grid grid-cols-2 gap-1">
                        @foreach(['Operator','Staff','Supervisor'] as $jab)
                            <label class="flex items-center gap-2">
                                <input type="checkbox"> {{ $jab }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="font-semibold mb-2">Status</h3>
                    <div class="grid grid-cols-2 gap-1">
                        <label><input type="checkbox"> Aktif</label>
                        <label><input type="checkbox"> Resign</label>
                    </div>
                </div>

                <!-- Action -->
                <div class="flex justify-between pt-4">
                    <button onclick="toggleFilter()"
                            class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300
                                   dark:bg-[#1f2937] dark:hover:bg-[#374151] transition">
                        Reset
                    </button>
                    <button onclick="toggleFilter()"
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">
                        Terapkan
                    </button>
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
