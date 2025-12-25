@extends('layouts.admin')

@section('title', 'Approval Bon Gaji')

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                Approval Bon Gaji
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pengajuan bon gaji karyawan yang menunggu persetujuan
            </p>
        </div>

        {{-- FILTER (KANAN) --}}
        <div class="flex items-center gap-3">
            <select
                class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-800
                       text-gray-700 dark:text-gray-200
                       focus:ring-2 focus:ring-blue-500 outline-none">
                <option>Status</option>
                <option>Menunggu</option>
                <option>Disetujui</option>
                <option>Ditolak</option>
            </select>

            <input type="month"
                   class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-800
                          text-gray-700 dark:text-gray-200
                          focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Karyawan</th>
                    <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-left font-semibold">Jumlah Bon</th>
                    <th class="px-6 py-4 text-left font-semibold">Alasan</th>
                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                {{-- ROW --}}
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                            Andi Pratama
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Finance
                        </p>
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        12 Oktober 2025
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">
                        Rp 1.500.000
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        Kebutuhan mendesak
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-yellow-100 text-yellow-700
                                     dark:bg-yellow-900/40 dark:text-yellow-300">
                            Menunggu
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button
                                class="px-3 py-1 rounded-lg text-xs font-semibold
                                       bg-green-600 hover:bg-green-700 text-white transition">
                                Setujui
                            </button>

                            <button
                                class="px-3 py-1 rounded-lg text-xs font-semibold
                                       bg-red-600 hover:bg-red-700 text-white transition">
                                Tolak
                            </button>

                            <button
                                class="px-3 py-1 rounded-lg text-xs font-semibold
                                       bg-gray-400 hover:bg-gray-500 text-white transition">
                                Batalkan
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- ROW --}}
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                            Siti Aisyah
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            HRD
                        </p>
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        10 Oktober 2025
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">
                        Rp 1.000.000
                    </td>

                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        Keperluan keluarga
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-green-100 text-green-700
                                     dark:bg-green-900/40 dark:text-green-300">
                            Disetujui
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">
                        —
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
@endsection
