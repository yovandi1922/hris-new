@extends('layouts.admin')
@section('title', 'Lembur')

@section('content')

<style>
/* ================= FILTER SLIDE (SAMA SEPERTI ABSENSI & KEPEGAWAIAN) ================= */
.slide-filter {
    position: fixed;
    top: 0;
    right: 0;
    width: 360px;
    height: 100%;
    background: white;
    transform: translateX(100%);
    transition: transform .35s ease;
    z-index: 200;
    box-shadow: -4px 0 20px rgba(0,0,0,.15);
    padding: 24px;
    overflow-y: auto;
}

.slide-filter.active {
    transform: translateX(0);
}

.dark .slide-filter {
    background: #111827;
    color: #f3f4f6;
    box-shadow: -4px 0 25px rgba(0,0,0,.6);
}
</style>

<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">Lembur</h1>

{{-- SEARCH + FILTER --}}
<div class="flex items-center justify-end gap-3 mb-6">

    <div class="relative w-80">
        <input type="text" placeholder="Search Data Pengajuan Lembur"
               class="w-full px-4 py-2 rounded-full border dark:border-gray-700 
                      bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
        <i class="fa fa-search absolute right-4 top-2.5 text-gray-500 dark:text-gray-300"></i>
    </div>

    {{-- FILTER BUTTON (SAMA) --}}
    <button onclick="toggleFilter(true)"
        class="px-4 py-2 rounded-full border dark:border-gray-600 
               text-gray-800 dark:text-white 
               hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        <i class="fa fa-filter"></i>
    </button>

</div>

{{-- ================= TABLE ================= --}}
<div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">
    <table class="w-full">
        <thead>
            <tr class="text-left text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                <th class="py-3">Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Durasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th class="text-right w-48">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 dark:text-gray-100">

            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                <td class="py-3">
                    <a href="{{ route('admin.approval.detaillembur') }}"
                       class="text-blue-600 hover:underline">
                        Agus Widanta
                    </a>
                </td>
                <td>18 Sept 2025</td>
                <td>19:00 - 22:00</td>
                <td>3 Jam</td>
                <td>Pengecekan server</td>
                <td>
                    <span class="text-green-600 dark:text-green-400">Disetujui</span>
                </td>
                <td class="py-3 text-right">
                    <button class="px-3 py-1 bg-gray-600 text-white rounded-lg text-sm">
                        Batalkan
                    </button>
                </td>
            </tr>

        </tbody>
    </table>
</div>

{{-- ================= FILTER SLIDE PANEL ================= --}}
<div id="filterSlide" class="slide-filter">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Filter Lembur</h2>
        <button onclick="toggleFilter(false)" class="text-2xl">&times;</button>
    </div>

    {{-- STATUS --}}
    <div class="mb-6">
        <p class="font-semibold mb-2">Status</p>
        <div class="space-y-2">
            <label class="flex gap-2"><input type="checkbox"> Disetujui</label>
            <label class="flex gap-2"><input type="checkbox"> Ditolak</label>
            <label class="flex gap-2"><input type="checkbox"> Menunggu</label>
        </div>
    </div>

    {{-- TANGGAL --}}
    <div class="mb-8">
        <p class="font-semibold mb-2">Tanggal</p>
        <input type="date"
            class="w-full px-3 py-2 border rounded-lg
                   dark:bg-gray-800 dark:border-gray-700 dark:text-white">
    </div>

    <div class="flex justify-between">
        <button class="px-4 py-2 border rounded-lg dark:border-gray-600">
            Reset
        </button>
        <button class="px-4 py-2 bg-black text-white rounded-lg dark:bg-white dark:text-black">
            Terapkan
        </button>
    </div>

</div>

<script>
function toggleFilter(open) {
    const panel = document.getElementById('filterSlide');
    open ? panel.classList.add('active')
         : panel.classList.remove('active');
}
</script>

@endsection
