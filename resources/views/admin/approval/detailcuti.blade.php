@extends('layouts.admin')
@section('title', 'Detail Cuti dan Izin')

@section('content')

<div class="space-y-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
        Cuti dan Izin
    </h1>

    {{-- BREADCRUMB --}}
    <div class="flex justify-end">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Dashboard / Persetujuan / Cuti dan Izin / Detail
        </p>
    </div>

    {{-- DETAIL CARD --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 
                bg-white dark:bg-gray-800 p-10 shadow-sm">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-8">
            Detail Pengajuan Cuti / Ijin
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-y-10">

            {{-- NAMA --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Andi Pratama
                </p>
            </div>

            {{-- NIP --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">NIP</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    19820310
                </p>
            </div>

            {{-- DEPARTEMEN --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Departemen</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Finance
                </p>
            </div>

            {{-- JABATAN --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jabatan</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Staff Keuangan
                </p>
            </div>

            {{-- JENIS CUTI / IJIN --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Cuti / Izin</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Cuti Tahunan
                </p>
            </div>

            {{-- TANGGAL MULAI --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    23/9/2025
                </p>
            </div>

            {{-- TANGGAL SELESAI --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    23/9/2025
                </p>
            </div>

            {{-- DURASI --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Durasi</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    1 Hari
                </p>
            </div>

            {{-- KETERANGAN --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Liburan Keluarga
                </p>
            </div>

            {{-- STATUS --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    Menunggu
                </p>
            </div>

            {{-- LAMPIRAN --}}
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lampiran</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    -
                </p>
            </div>

        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex gap-4">
        <button class="px-6 py-2 rounded-lg border border-green-600 text-green-600 
                dark:border-green-500 dark:text-green-500 
                hover:bg-green-50 dark:hover:bg-green-900/20">
            ✔ Setujui
        </button>

        <button class="px-6 py-2 rounded-lg border border-red-600 text-red-600
                dark:border-red-500 dark:text-red-500
                hover:bg-red-50 dark:hover:bg-red-900/20">
            ✖ Tolak
        </button>
    </div>

</div>
@endsection
