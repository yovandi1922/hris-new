@extends('layouts.admin')
@section('title', 'Jadwal Kerja')
@section('content')
<div class="p-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jadwal Kerja Karyawan</h1>
        <div class="flex gap-2">
            <input type="text" placeholder="Cari NIP atau Nama..." 
                   class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:text-white">
            <button onclick="toggleFilterPanel()" 
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg shadow transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <div class="flex gap-6">

        <!-- Tabel Jadwal -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow rounded-lg p-6 overflow-x-auto">
            @php
                $jadwal = [
                    ['nip'=>'001','name'=>'Ahmad Fauzi','departemen'=>'HRD','jabatan'=>'Staff','senin'=>'08:00-17:00','selasa'=>'08:00-17:00','rabu'=>'08:00-17:00','kamis'=>'08:00-17:00','jumat'=>'08:00-17:00'],
                    ['nip'=>'002','name'=>'Siti Aisyah','departemen'=>'Produksi','jabatan'=>'Operator','senin'=>'07:00-16:00','selasa'=>'07:00-16:00','rabu'=>'07:00-16:00','kamis'=>'07:00-16:00','jumat'=>'07:00-16:00'],
                    ['nip'=>'003','name'=>'Budi Santoso','departemen'=>'Quality Control','jabatan'=>'Supervisor','senin'=>'09:00-18:00','selasa'=>'09:00-18:00','rabu'=>'09:00-18:00','kamis'=>'09:00-18:00','jumat'=>'09:00-18:00'],
                ];
            @endphp

            <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">NIP</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Departemen</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jabatan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Senin</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Selasa</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Rabu</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Kamis</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jumat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $item['nip'] }}</td>
                            <td class="px-4 py-2 font-semibold text-gray-800 dark:text-gray-100">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['departemen'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['jabatan'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['senin'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['selasa'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['rabu'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['kamis'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $item['jumat'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Panel Filter -->
        <div id="filterPanel" class="hidden w-[380px] bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 border border-gray-300 dark:border-gray-700 transition-all duration-300">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Filter Jadwal</h2>

            <!-- Departemen -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Departemen</h3>
                <div class="grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-300">
                    @foreach(['Semua','HRD','Produksi','Quality Control','Gudang','Akutansi','Design','Keamanan'] as $dept)
                        <label class="flex items-center gap-2"><input type="checkbox"> {{ $dept }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Jabatan -->
            <div class="mb-4">
                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Jabatan</h3>
                <div class="grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-300">
                    @foreach(['Semua','Operator','Staff','Supervisor'] as $jab)
                        <label class="flex items-center gap-2"><input type="checkbox"> {{ $jab }}</label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="toggleFilterPanel()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded-full hover:bg-gray-400">Tutup</button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilterPanel() {
    document.getElementById('filterPanel').classList.toggle('hidden');
}
</script>
@endsection
