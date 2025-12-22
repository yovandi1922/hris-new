@extends('layouts.admin')
@section('title', 'Gaji')

@section('content')
<div class="space-y-8">

    {{-- PAGE TITLE & BREADCRUMB --}}
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Gaji
        </h1>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            Dashboard / Gaji
        </span>
    </div>

    {{-- SLIP GAJI CARD --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-8 space-y-6">

        {{-- PERIODE --}}
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            1 Agustus 2025 - 31 Agustus 2025
        </h2>

        {{-- DATA KARYAWAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-1 text-gray-700 dark:text-gray-300">
                <p><span class="font-medium">Nama</span> : Fira Yunanda</p>
                <p><span class="font-medium">NIP</span> : 1329</p>
                <p><span class="font-medium">Departemen</span> : Administrasi</p>
            </div>
            <div class="space-y-1 text-gray-700 dark:text-gray-300">
                <p><span class="font-medium">Jabatan</span> : Staff</p>
                <p><span class="font-medium">Tanggal Gabung</span> : 12 Januari 2022</p>
                <p><span class="font-medium">No. Rekening</span> : BCA - 1234567890 a.n. Fira Yunanda</p>
            </div>
        </div>

        {{-- PENDAPATAN & POTONGAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PENDAPATAN --}}
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-blue-600 text-white px-4 py-2 font-semibold">
                    Pendapatan
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="flex justify-between px-4 py-2 text-gray-700 dark:text-gray-300">
                        <span>Gaji Pokok</span><span>Rp 3.000.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>Tunjangan Transport</span><span>Rp 300.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>Tunjangan Makan</span><span>Rp 250.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>Lembur</span><span>Rp 90.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 font-semibold bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-200">
                        <span>Total Pendapatan</span><span>Rp 3.640.000</span>
                    </div>
                </div>
            </div>

            {{-- POTONGAN --}}
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-blue-600 text-white px-4 py-2 font-semibold">
                    Potongan
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="flex justify-between px-4 py-2 text-gray-700 dark:text-gray-300">
                        <span>BPJS Kesehatan</span><span>Rp 120.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>PPh 21 (Pajak)</span><span>Rp 50.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>Potongan Alfa (1 Hari)</span><span>Rp 115.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-2">
                        <span>Potongan Terlambat (3x)</span><span>Rp 30.000</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 font-semibold bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-200">
                        <span>Total Diterima</span><span>Rp 3.325.000</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- DOWNLOAD --}}
        <div>
            <button class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg transition">
                <i class="fas fa-download"></i>
                Download Slip Gaji
            </button>
        </div>
    </div>

    {{-- RIWAYAT BON GAJI --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Riwayat Bon Gaji
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Bulan / Tahun</th>
                        <th class="px-4 py-2 text-left">Tanggal Bayar</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                    <tr>
                        <td class="px-4 py-2">2 September</td>
                        <td class="px-4 py-2">Rp 800.000</td>
                        <td class="px-4 py-2">Biaya anak sekolah</td>
                        <td class="px-4 py-2 text-center">
                            <button class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                Lihat Slip
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">29 Agustus</td>
                        <td class="px-4 py-2">Rp 500.000</td>
                        <td class="px-4 py-2">Kebutuhan rumah</td>
                        <td class="px-4 py-2 text-center">
                            <button class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded">
                                Lihat Slip
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">22 Agustus</td>
                        <td class="px-4 py-2">Rp 750.000</td>
                        <td class="px-4 py-2">Biaya hidup</td>
                        <td class="px-4 py-2 text-center">
                            <button class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded">
                                Lihat Slip
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
