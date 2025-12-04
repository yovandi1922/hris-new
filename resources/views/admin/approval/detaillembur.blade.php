@extends('layouts.admin')
@section('title', 'Detail Lembur')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">Lembur</h1>

<div class="p-8 bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

    <h2 class="text-xl font-semibold mb-6 dark:text-white">Detail Pengajuan Lembur</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-gray-700 dark:text-gray-200">

        <div>
            <p class="font-semibold">Nama</p>
            <p>Rina Marlina</p>
        </div>

        <div>
            <p class="font-semibold">NIP</p>
            <p>20231252</p>
        </div>

        <div>
            <p class="font-semibold">Departemen</p>
            <p>Quality Assurance</p>
        </div>

        <div>
            <p class="font-semibold">Jabatan</p>
            <p>Quality Inspector</p>
        </div>

        <div>
            <p class="font-semibold">Tanggal</p>
            <p>21 September 2025</p>
        </div>

        <div>
            <p class="font-semibold">Waktu Mulai</p>
            <p>17:00</p>
        </div>

        <div>
            <p class="font-semibold">Waktu Selesai</p>
            <p>20:00</p>
        </div>

        <div>
            <p class="font-semibold">Durasi</p>
            <p>3 Jam</p>
        </div>

        <div class="col-span-2">
            <p class="font-semibold">Keterangan</p>
            <p>Audit pengecekan 400 unit produk...</p>
        </div>

        <div>
            <p class="font-semibold">Status</p>
            <p>Menunggu</p>
        </div>

        <div>
            <p class="font-semibold">Lampiran</p>
            <div class="flex items-center gap-2 border rounded-lg px-3 py-1 w-max dark:border-gray-600">
                <i class="fa fa-image"></i>
                <span>Screenshot_2192025.jpg</span>
            </div>
        </div>
    </div>

</div>

<div class="flex gap-4 mt-6">
    <button class="px-6 py-2 bg-green-600 text-white rounded-lg flex items-center gap-2">
        ✔ Setujui
    </button>

    <button class="px-6 py-2 bg-red-600 text-white rounded-lg flex items-center gap-2">
        ✖ Tolak
    </button>
</div>

@endsection
