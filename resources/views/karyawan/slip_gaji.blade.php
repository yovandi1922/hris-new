@extends('layouts.karyawan')

@section('title', 'Slip Gaji')

@section('content')
<div class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    {{-- Breadcrumb --}}
    <div class="mb-6 text-base text-gray-600 dark:text-gray-400 text-right">
        <a href="{{ route('karyawan.index') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('karyawan.pengajuan') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Pengajuan</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 dark:text-gray-100">Slip Gaji</span>
    </div>

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Slip Gaji</h1>
            <p class="text-gray-500 dark:text-gray-400">Lihat detail slip gaji anda di sini.</p>
        </div>

        {{-- FILTER BULAN - SETENGAH LAYAR --}}
        <div class="
            bg-white dark:bg-gray-800
            shadow-md rounded-2xl p-6
            ring-1 ring-gray-200 dark:ring-gray-700
            max-w-md
        ">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Bulan</label>
            <select class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700
                           border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
                <option>Desember 2025</option>
                <option>November 2025</option>
                <option>Oktober 2025</option>
                <option>September 2025</option>
                <option>Agustus 2025</option>
            </select>
        </div>

        {{-- SLIP GAJI PREVIEW --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8 ring-1 ring-gray-200 dark:ring-gray-700">

            {{-- Header Slip --}}
            <div class="mb-8 pb-6 border-b border-gray-300 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">Slip Gaji Desember 2025</h2>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <div>
                        <p class="font-semibold">Nama Karyawan:</p>
                        <p>{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">NIK:</p>
                        <p>2024001</p>
                    </div>
                    <div>
                        <p class="font-semibold">Departemen:</p>
                        <p>IT Development</p>
                    </div>
                    <div>
                        <p class="font-semibold">Periode:</p>
                        <p>1 - 31 Desember 2025</p>
                    </div>
                </div>
            </div>

            {{-- Pendapatan --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Pendapatan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Gaji Pokok</span>
                        <span>Rp 4.500.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Tunjangan Kesehatan</span>
                        <span>Rp 500.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Tunjangan Makan</span>
                        <span>Rp 300.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Lembur</span>
                        <span>Rp 200.000</span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-800 dark:text-gray-100 pt-2 border-t border-gray-300 dark:border-gray-700">
                        <span>Total Pendapatan</span>
                        <span>Rp 5.500.000</span>
                    </div>
                </div>
            </div>

            {{-- Potongan --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Potongan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>BPJS Kesehatan</span>
                        <span>Rp 125.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>BPJS Ketenagakerjaan</span>
                        <span>Rp 75.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Pajak Penghasilan (PPh 21)</span>
                        <span>Rp 200.000</span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-800 dark:text-gray-100 pt-2 border-t border-gray-300 dark:border-gray-700">
                        <span>Total Potongan</span>
                        <span>Rp 400.000</span>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <div class="flex justify-between items-center text-lg font-bold text-gray-900 dark:text-gray-100">
                    <span>Gaji Bersih (Take Home Pay)</span>
                    <span class="text-green-600 dark:text-green-400">Rp 5.100.000</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex gap-4 justify-center">
                <button class="px-6 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg
                               hover:opacity-90 transition font-semibold">
                    <i class="fa-solid fa-download mr-2"></i>
                    Download PDF
                </button>
                <button class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg
                               hover:opacity-90 transition font-semibold">
                    <i class="fa-solid fa-print mr-2"></i>
                    Cetak
                </button>
            </div>

        </div>

        {{-- RIWAYAT SLIP GAJI --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 ring-1 ring-gray-200 dark:ring-gray-700">
            <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Riwayat Slip Gaji</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Periode</th>
                            <th class="px-4 py-3 text-left font-semibold">Gaji Pokok</th>
                            <th class="px-4 py-3 text-left font-semibold">Tunjangan</th>
                            <th class="px-4 py-3 text-left font-semibold">Potongan</th>
                            <th class="px-4 py-3 text-left font-semibold">Gaji Bersih</th>
                            <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <td class="px-4 py-3">Desember 2025</td>
                            <td class="px-4 py-3">Rp 4.500.000</td>
                            <td class="px-4 py-3">Rp 1.000.000</td>
                            <td class="px-4 py-3">Rp 400.000</td>
                            <td class="px-4 py-3 font-semibold">Rp 5.100.000</td>
                            <td class="px-4 py-3">
                                <button class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Lihat</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">November 2025</td>
                            <td class="px-4 py-3">Rp 4.500.000</td>
                            <td class="px-4 py-3">Rp 1.000.000</td>
                            <td class="px-4 py-3">Rp 400.000</td>
                            <td class="px-4 py-3 font-semibold">Rp 5.100.000</td>
                            <td class="px-4 py-3">
                                <button class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Lihat</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Oktober 2025</td>
                            <td class="px-4 py-3">Rp 4.500.000</td>
                            <td class="px-4 py-3">Rp 1.000.000</td>
                            <td class="px-4 py-3">Rp 400.000</td>
                            <td class="px-4 py-3 font-semibold">Rp 5.100.000</td>
                            <td class="px-4 py-3">
                                <button class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
