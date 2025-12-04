@extends('layouts.admin')
@section('title', 'Cuti dan Izin')

@section('content')

<style>
    .slide-filter {
        position: fixed;
        top:0;
        right:0;
        width: 320px;
        height:100%;
        background:white;
        color:#111;
        box-shadow:-4px 0 20px rgba(0,0,0,0.1);
        padding:20px;
        transform: translateX(100%);
        transition: .3s ease;
        z-index: 200;
        overflow-y:auto;
    }
    .slide-filter.active {
        transform: translateX(0);
    }

    /* Dark Mode */
    .dark .slide-filter {
        background:#1f2937;
        color:#f3f4f6;
        box-shadow:-4px 0 20px rgba(0,0,0,0.6);
    }

    .filter-checkbox {
        display:flex;
        align-items:center;
        gap:8px;
    }
</style>

<h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100">Cuti dan Izin</h1>

{{-- ===================== HEADER RIGHT ======================== --}}
<div class="flex items-center justify-end mb-6 gap-3">

    {{-- SEARCH BOX --}}
    <div class="relative">
        <input 
            type="text" 
            class="border dark:border-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-full py-2 pl-4 pr-10 w-72"
            placeholder="Search Data Pengajuan Cuti & Izin">
        <span class="absolute right-3 top-2.5 text-gray-500 dark:text-gray-300">
            <i class="fa fa-search"></i>
        </span>
    </div>

    {{-- FILTER BUTTON --}}
    <button id="btnFilter" 
        class="border dark:border-gray-700 dark:text-gray-200 rounded-full px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
        <i class="fa fa-filter text-xl"></i>
    </button>
</div>


{{-- ===================== TABEL CUTI & IZIN ======================== --}}
<div class="overflow-x-auto border dark:border-gray-700 rounded-lg">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200 font-semibold">
                <th class="p-3 border dark:border-gray-700">Nama Karyawan</th>
                <th class="p-3 border dark:border-gray-700">Jenis</th>
                <th class="p-3 border dark:border-gray-700">Durasi</th>
                <th class="p-3 border dark:border-gray-700">Durasi</th>
                <th class="p-3 border dark:border-gray-700">Keterangan</th>
                <th class="p-3 border dark:border-gray-700">Status</th>
                <th class="p-3 border dark:border-gray-700 w-40">Aksi</th>
            </tr>
        </thead>

        <tbody class="text-gray-900 dark:text-gray-200">
            {{-- DATA EXAMPLE --}}
            <tr>
                <td class="p-3 border dark:border-gray-700">Andi Pratama</td>
                <td class="p-3 border dark:border-gray-700">Cuti Tahunan</td>
                <td class="p-3 border dark:border-gray-700">23/9/2025 - 23/9/2025</td>
                <td class="p-3 border dark:border-gray-700">1 Hari</td>
                <td class="p-3 border dark:border-gray-700">Liburan Keluarga</td>
                <td class="p-3 border dark:border-gray-700">Menunggu</td>
                <td class="p-3 border dark:border-gray-700">
                    <button class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 px-3 py-1 rounded-full text-sm">Setujui</button>
                    <button class="bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 px-3 py-1 rounded-full text-sm">Tolak</button>
                </td>
            </tr>

            <tr>
                <td class="p-3 border dark:border-gray-700">Siti Nurhaliza</td>
                <td class="p-3 border dark:border-gray-700">Izin Sakit</td>
                <td class="p-3 border dark:border-gray-700">2/9/2025 - 4/9/2025</td>
                <td class="p-3 border dark:border-gray-700">2 Hari</td>
                <td class="p-3 border dark:border-gray-700">Sakit Tipes</td>
                <td class="p-3 border dark:border-gray-700">Disetujui</td>
                <td class="p-3 border dark:border-gray-700">
                    <button class="bg-gray-200 dark:bg-gray-700 dark:text-gray-200 px-3 py-1 rounded-full text-sm">Batalkan</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>



{{-- ===================== FILTER SLIDE PANEL ======================== --}}
<div id="filterSlide" class="slide-filter">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Filter</h2>
        <button id="closeFilter" class="text-xl">&times;</button>
    </div>

    <div class="space-y-6">

        {{-- Jenis Cuti — tidak diubah --}}
        <div>
            <p class="font-semibold mb-2">Jenis Cuti</p>

            <div class="grid grid-cols-2 gap-3">
                <label class="filter-checkbox"><input type="checkbox"> Cuti Pribadi</label>
                <label class="filter-checkbox"><input type="checkbox"> Izin Sakit</label>
                <label class="filter-checkbox"><input type="checkbox"> Cuti Tahunan</label>
                <label class="filter-checkbox"><input type="checkbox"> Izin Pribadi</label>
                <label class="filter-checkbox"><input type="checkbox"> Cuti Melahirkan</label>
            </div>
        </div>

        {{-- Status — tidak diubah --}}
        <div>
            <p class="font-semibold mb-2">Status</p>

            <div class="grid grid-cols-2 gap-3">
                <label class="filter-checkbox"><input type="checkbox"> Disetujui</label>
                <label class="filter-checkbox"><input type="checkbox"> Ditolak</label>
                <label class="filter-checkbox"><input type="checkbox"> Menunggu</label>
            </div>
        </div>

        {{-- Tanggal — tidak diubah --}}
        <div>
            <p class="font-semibold mb-2">Tanggal</p>
            <input type="date" 
                class="border dark:border-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 w-full p-2 rounded">
        </div>

        <div class="flex justify-between mt-5">
            <button class="px-4 py-2 border dark:border-gray-600 rounded-lg dark:text-gray-200">Reset</button>
            <button class="px-4 py-2 bg-black dark:bg-white dark:text-black text-white rounded-lg">Terapkan</button>
        </div>
    </div>
</div>


{{-- ===================== JS OPEN & CLOSE FILTER ======================== --}}
<script>
    document.getElementById('btnFilter').onclick = () => {
        document.getElementById('filterSlide').classList.add('active')
    }
    document.getElementById('closeFilter').onclick = () => {
        document.getElementById('filterSlide').classList.remove('active')
    }
</script>

@endsection
