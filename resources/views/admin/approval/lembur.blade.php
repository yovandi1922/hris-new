@extends('layouts.admin')
@section('title', 'Lembur')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">Lembur</h1>

{{-- SEARCH + FILTER BUTTON --}}
<div class="flex items-center justify-end gap-3 mb-6">

    {{-- SEARCH --}}
    <div class="relative w-80">
        <input type="text" placeholder="Search Data Pengajuan Lembur"
               class="w-full px-4 py-2 rounded-full border dark:border-gray-700 
                      bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
        <i class="fa fa-search absolute right-4 top-2.5 text-gray-500 dark:text-gray-300"></i>
    </div>

    {{-- FILTER BUTTON --}}
    <button onclick="toggleFilter()"
        class="px-4 py-2 rounded-lg border dark:border-gray-600 text-gray-800 dark:text-white 
               flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        <i class="fa fa-filter"></i>
    </button>

</div>

{{-- FILTER PANEL --}}
<div id="filter-panel"
     class="hidden fixed inset-0 bg-black/40 flex justify-end items-start pt-24 pr-10 z-50">

    <div class="w-[420px] bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Filter</h2>
            <button onclick="toggleFilter()" class="text-gray-700 dark:text-gray-300 text-xl">×</button>
        </div>

        {{-- STATUS --}}
        <div class="mb-4">
            <p class="font-semibold text-gray-800 dark:text-gray-200">Status</p>
            <div class="mt-2 grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-300">
                <label><input type="checkbox"> Disetujui</label>
                <label><input type="checkbox"> Ditolak</label>
                <label><input type="checkbox"> Menunggu</label>
            </div>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-6">
            <p class="font-semibold text-gray-800 dark:text-gray-200">Tanggal</p>
            <input type="date"
                class="mt-2 w-full px-3 py-2 rounded-lg border dark:border-gray-700 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
        </div>

        {{-- ACTION --}}
        <div class="flex justify-between">
            <button class="px-4 py-2 border rounded-lg dark:text-white dark:border-gray-600">Reset</button>
            <button class="px-4 py-2 bg-black text-white rounded-lg">Terapkan</button>
        </div>

    </div>
</div>

{{-- TABLE --}}
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

            {{-- ROW 1 — Menunggu --}}
            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer"
                onclick="goDetail(event, '/persetujuan/lembur/detail')">

                <td class="py-3">Rina Marlina</td>
                <td>21 Sept 2025</td>
                <td>18:00 - 21:00</td>
                <td>3 Jam</td>
                <td>Penyelesaian laporan bulanan</td>
                <td><span class="text-yellow-600 dark:text-yellow-400">Menunggu</span></td>

                <td class="py-3 text-right space-x-1">

                    {{-- SETUJUI --}}
                    <button onclick="event.stopPropagation(); alert('Disetujui!')"
                        class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Setujui
                    </button>

                    {{-- TOLAK --}}
                    <button onclick="event.stopPropagation(); alert('Ditolak!')"
                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                        Tolak
                    </button>

                </td>
            </tr>

            {{-- ROW 2 — Disetujui --}}
            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer"
                onclick="goDetail(event, '/persetujuan/lembur/detail')">

                <td class="py-3">Agus Widanta</td>
                <td>18 Sept 2025</td>
                <td>19:00 - 22:00</td>
                <td>3 Jam</td>
                <td>Pengecekan server</td>
                <td><span class="text-green-600 dark:text-green-400">Disetujui</span></td>

                <td class="py-3 text-right space-x-1">

                    {{-- BATALKAN --}}
                    <button onclick="event.stopPropagation(); alert('Dibatalkan!')"
                        class="px-3 py-1 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">
                        Batalkan
                    </button>

                    {{-- TOLAK (opsional jika status bisa berubah) --}}
                    <button onclick="event.stopPropagation(); alert('Ditolak!')"
                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                        Tolak
                    </button>

                </td>
            </tr>

        </tbody>
    </table>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    // Mencegah klik tombol ikut mengklik baris
    function goDetail(event, url) {
        if (!event.target.closest("button")) {
            window.location = url;
        }
    }
</script>

@endsection
