@extends('layouts.karyawan')

@section('title', 'Bon Gaji')

@section('content')
<div class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    {{-- Breadcrumb --}}
    <div class="mb-6 text-base text-gray-600 dark:text-gray-400 text-right">
        <a href="{{ route('karyawan.index') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('karyawan.pengajuan') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Pengajuan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 dark:text-gray-100">Bon Gaji</span>
    </div>

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Pengajuan Bon Gaji</h1>
            <p class="text-gray-500 dark:text-gray-400">Lihat dan ajukan bon gaji anda di sini.</p>
        </div>

        {{-- RINGKASAN BON GAJI - SETENGAH LAYAR --}}
        <div class="
            bg-white dark:bg-gray-800
            shadow-md rounded-2xl p-6
            grid grid-cols-3 gap-2
            ring-1 ring-gray-200 dark:ring-gray-700
            max-w-md
        ">
            {{-- Total Bon --}}
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Bon</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">7</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">Kali</p>
            </div>

            {{-- Disetujui --}}
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Disetujui</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">5</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">Kali</p>
            </div>

            {{-- Ditolak --}}
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Ditolak</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">2</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">Kali</p>
            </div>

            {{-- Tombol Ajukan --}}
            <div class="col-span-3 mt-2 flex justify-center">
                <button id="btnTambah"
                   class="px-4 py-1.5 rounded-full bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm
                          shadow hover:opacity-80 transition">
                    Ajukan Bon Gaji
                </button>
            </div>
        </div>

        {{-- HISTORY --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 ring-1 ring-gray-200 dark:ring-gray-700">
            <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Riwayat Bon Gaji</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Nominal</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <td class="px-4 py-3">5 November 2025</td>
                            <td class="px-4 py-3">Rp 500.000</td>
                            <td class="px-4 py-3">Biaya mendesak</td>
                            <td class="px-4 py-3 text-green-600 dark:text-green-400 font-semibold">Disetujui</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">20 Oktober 2025</td>
                            <td class="px-4 py-3">Rp 1.000.000</td>
                            <td class="px-4 py-3">Biaya keluarga</td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-400 font-semibold">Ditolak</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">15 Oktober 2025</td>
                            <td class="px-4 py-3">Rp 750.000</td>
                            <td class="px-4 py-3">Biaya hidup</td>
                            <td class="px-4 py-3 text-yellow-600 dark:text-yellow-400 font-semibold">Menunggu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- MODAL FORM --}}
<div id="modalBon" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Ajukan Bon Gaji</h2>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal</label>
                <input type="number"
                    class="w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100"
                    placeholder="Masukkan nominal">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                <textarea rows="3"
                    class="w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100"
                    placeholder="Tuliskan keterangan"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="btnBatal"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100
                           hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900
                           hover:opacity-90 transition">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnTambah').addEventListener('click', function() {
        document.getElementById('modalBon').classList.remove('hidden');
    });

    document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('modalBon').classList.add('hidden');
    });
</script>
@endsection
