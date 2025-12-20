@extends('layouts.admin')
@section('title', 'Cuti dan Izin')

@section('content')

{{-- ===================== STYLE ===================== --}}
<style>
    .slide-filter {
        position: fixed;
        top: 0;
        right: 0;
        width: 340px;
        height: 100%;
        background: #ffffff;
        box-shadow: -6px 0 24px rgba(0,0,0,0.15);
        padding: 24px;
        transform: translateX(100%);
        transition: transform .35s ease;
        z-index: 200;
        overflow-y: auto;
    }

    .slide-filter.active {
        transform: translateX(0);
    }

    .dark .slide-filter {
        background: #111827;
        color: #f3f4f6;
        box-shadow: -6px 0 24px rgba(0,0,0,0.6);
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }
</style>

{{-- ===================== PAGE TITLE ===================== --}}
<h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100">
    Cuti dan Izin
</h1>

{{-- ===================== HEADER RIGHT (SEARCH + FILTER) ===================== --}}
<div class="flex items-center justify-between mb-6">

    {{-- SEARCH --}}
    <div class="relative w-80">
        <input type="text"
            placeholder="Cari nama karyawan / jenis cuti"
            class="w-full rounded-full border dark:border-gray-700
                   bg-white dark:bg-gray-800
                   text-gray-700 dark:text-gray-200
                   py-2.5 pl-4 pr-10 focus:outline-none">
        <span class="absolute right-4 top-3 text-gray-400">
            <i class="fas fa-search"></i>
        </span>
    </div>

    {{-- FILTER BUTTON --}}
    <button id="btnFilter"
        class="flex items-center gap-2 px-4 py-2.5
               bg-gray-200 hover:bg-gray-300
               dark:bg-gray-700 dark:hover:bg-gray-600
               text-gray-700 dark:text-gray-200
               rounded-full shadow transition">
        <i class="fas fa-filter"></i>
        <span class="text-sm font-medium">Filter</span>
    </button>
</div>

{{-- ===================== TABLE ===================== --}}
<div class="overflow-x-auto rounded-xl border dark:border-gray-700">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-3 border dark:border-gray-700">Nama Karyawan</th>
                <th class="p-3 border dark:border-gray-700">Jenis</th>
                <th class="p-3 border dark:border-gray-700">Tanggal</th>
                <th class="p-3 border dark:border-gray-700">Durasi</th>
                <th class="p-3 border dark:border-gray-700">Keterangan</th>
                <th class="p-3 border dark:border-gray-700">Status</th>
                <th class="p-3 border dark:border-gray-700 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 dark:text-gray-200">
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <td class="p-3 border dark:border-gray-700">
                    <a href="{{ route('admin.approval.detailcuti') }}"
                       class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                        Agus Widanta
                    </a>
                </td>
                <td class="p-3 border dark:border-gray-700">Cuti Tahunan</td>
                <td class="p-3 border dark:border-gray-700">18 Sept 2025</td>
                <td class="p-3 border dark:border-gray-700">3 Hari</td>
                <td class="p-3 border dark:border-gray-700">Acara keluarga</td>
                <td class="p-3 border dark:border-gray-700 text-green-500 font-semibold">
                    Disetujui
                </td>
                <td class="p-3 border dark:border-gray-700 text-center">
                    <button class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-xs">
                        Batalkan
                    </button>
                </td>
            </tr>

            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <td class="p-3 border dark:border-gray-700">Siti Nurhaliza</td>
                <td class="p-3 border dark:border-gray-700">Izin Sakit</td>
                <td class="p-3 border dark:border-gray-700">2–4 Sept 2025</td>
                <td class="p-3 border dark:border-gray-700">2 Hari</td>
                <td class="p-3 border dark:border-gray-700">Sakit Tipes</td>
                <td class="p-3 border dark:border-gray-700 text-green-500 font-semibold">
                    Disetujui
                </td>
                <td class="p-3 border dark:border-gray-700 text-center">
                    <button class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-xs">
                        Batalkan
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- ===================== SLIDE FILTER ===================== --}}
<div id="filterSlide" class="slide-filter">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Filter Data</h2>
        <button id="closeFilter" class="text-2xl">&times;</button>
    </div>

    <div class="space-y-6">

        {{-- JENIS CUTI --}}
        <div>
            <p class="font-semibold mb-3">Jenis Cuti</p>
            <div class="grid grid-cols-2 gap-3">
                <label class="filter-checkbox"><input type="checkbox"> Cuti Tahunan</label>
                <label class="filter-checkbox"><input type="checkbox"> Cuti Pribadi</label>
                <label class="filter-checkbox"><input type="checkbox"> Izin Sakit</label>
                <label class="filter-checkbox"><input type="checkbox"> Izin Pribadi</label>
                <label class="filter-checkbox"><input type="checkbox"> Melahirkan</label>
            </div>
        </div>

        {{-- STATUS --}}
        <div>
            <p class="font-semibold mb-3">Status</p>
            <div class="grid grid-cols-2 gap-3">
                <label class="filter-checkbox"><input type="checkbox"> Disetujui</label>
                <label class="filter-checkbox"><input type="checkbox"> Menunggu</label>
                <label class="filter-checkbox"><input type="checkbox"> Ditolak</label>
            </div>
        </div>

        {{-- TANGGAL --}}
        <div>
            <p class="font-semibold mb-3">Tanggal</p>
            <input type="date"
                class="w-full p-2 rounded-lg border
                       dark:border-gray-700
                       bg-white dark:bg-gray-800
                       text-gray-700 dark:text-gray-200">
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-between pt-4">
            <button
                class="px-4 py-2 rounded-lg border
                       dark:border-gray-600
                       hover:bg-gray-100 dark:hover:bg-gray-700">
                Reset
            </button>

            <button
                class="px-5 py-2 rounded-lg
                       bg-gray-900 text-white
                       dark:bg-white dark:text-black">
                Terapkan
            </button>
        </div>

    </div>
</div>

{{-- ===================== SCRIPT ===================== --}}
<script>
    const filterSlide = document.getElementById('filterSlide');
    document.getElementById('btnFilter').onclick = () => {
        filterSlide.classList.add('active');
    }
    document.getElementById('closeFilter').onclick = () => {
        filterSlide.classList.remove('active');
    }
</script>

@endsection
