@extends('layouts.karyawan')

@section('title', 'Pengajuan - Cuti & Izin')

@section('content')
<div x-data="{ openModal: false }" class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

    {{-- HEADER JUDUL --}}
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Cuti dan Izin</h1>

    {{-- RINGKASAN CUTI --}}
    <div class="
        bg-white dark:bg-gray-800
        shadow-md rounded-2xl p-6
        flex flex-col md:flex-row items-center justify-between gap-8
        ring-1 ring-gray-200 dark:ring-gray-700
    ">
        {{-- Sisa Cuti --}}
        <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Cuti</p>
            <p class="text-4xl font-bold mt-1 text-gray-800 dark:text-gray-100">8</p>
            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">Hari</p>
        </div>

        <div class="hidden md:block h-10 w-px bg-gray-300 dark:bg-gray-700"></div>

        {{-- Diambil --}}
        <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">Diambil</p>
            <p class="text-4xl font-bold mt-1 text-gray-800 dark:text-gray-100">4</p>
            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">Hari</p>
        </div>

        <div class="hidden md:block h-10 w-px bg-gray-300 dark:bg-gray-700"></div>

        {{-- Ditolak --}}
        <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">Ditolak</p>
            <p class="text-4xl font-bold mt-1 text-gray-800 dark:text-gray-100">-</p>
            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">Hari</p>
        </div>

        {{-- Tombol Ajukan --}}
        <div>
            <button @click="openModal = true"
               class="px-5 py-2 rounded-full bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900
                      shadow hover:opacity-80 transition">
                Ajukan Cuti/Izin
            </button>
        </div>
    </div>

    {{-- RIWAYAT CUTI --}}
    <div class="mt-10
        bg-white dark:bg-gray-800
        shadow-md rounded-2xl p-0
        ring-1 ring-gray-200 dark:ring-gray-700">

        {{-- Judul --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Cuti</h2>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold">Jenis</th>
                        <th class="px-6 py-3 text-left font-semibold">Durasi</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-left font-semibold">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">

                    <tr>
                        <td class="px-6 py-4">10–12 September</td>
                        <td class="px-6 py-4">Cuti Tahunan</td>
                        <td class="px-6 py-4">2 Hari</td>
                        <td class="px-6 py-4 text-green-600 dark:text-green-400 font-semibold">Disetujui</td>
                        <td class="px-6 py-4">-</td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4">29 Agustus</td>
                        <td class="px-6 py-4">Izin Sakit</td>
                        <td class="px-6 py-4">1 Hari</td>
                        <td class="px-6 py-4 text-yellow-600 dark:text-yellow-400 font-semibold">Menunggu</td>
                        <td class="px-6 py-4">Surat RS</td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4">5 Agustus</td>
                        <td class="px-6 py-4">Cuti Pribadi</td>
                        <td class="px-6 py-4">1 Hari</td>
                        <td class="px-6 py-4 text-red-600 dark:text-red-400 font-semibold">Ditolak</td>
                        <td class="px-6 py-4">Tidak Valid</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>



    {{-- ===================== MODAL FORM ===================== --}}
    <div
        x-show="openModal"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center">

        {{-- Background Overlay --}}
        <div
            class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm"
            @click="openModal = false">
        </div>

        {{-- Card Modal --}}
        <div
            x-show="openModal"
            x-transition
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-3xl p-8 z-50">

            <h2 class="text-xl font-semibold mb-6 text-center text-gray-800 dark:text-gray-100">
                Form Pengajuan Cuti/Izin
            </h2>

            <form x-data="{
                    start:'',
                    end:'',
                    duration:0,
                    formatDate(d){
                        const date = new Date(d);
                        const options = { day:'numeric', month:'long', year:'numeric' };
                        return date.toLocaleDateString('id-ID', options);
                    }
                }"
                class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Jenis Pengajuan --}}
                <div>
                    <label class="font-medium">Jenis Pengajuan<span class="text-red-500">*</span></label>
                    <select class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                   border-gray-300 dark:border-gray-600
                                   text-gray-800 dark:text-gray-100">
                        <option>Pilih Pengajuan</option>
                        <option>Cuti Tahunan</option>
                        <option>Cuti Pribadi</option>
                        <option>Izin Sakit</option>
                        <option>Izin Lainnya</option>
                    </select>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="font-medium">Keterangan<span class="text-red-500">*</span></label>
                    <textarea rows="3" class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-800 dark:text-gray-100"
                              placeholder="Tulis keterangan"></textarea>
                </div>

                {{-- Rentang Tanggal --}}
                <div x-data="{ openCalendar:false }" class="relative">
                    <label class="font-medium">Rentang Tanggal<span class="text-red-500">*</span></label>

                    {{-- Tombol --}}
                    <button
                        type="button"
                        @click="openCalendar = true"
                        class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                               flex justify-between items-center
                               border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
                        <span x-text="start && end ? formatDate(start) + ' - ' + formatDate(end) : 'Pilih Rentang Tanggal'"></span>
                        <i class="fa-solid fa-calendar"></i>
                    </button>

                    {{-- Popup Kalender --}}
                    <div x-show="openCalendar"
                        x-transition
                        class="absolute left-0 right-0 mt-2 bg-white dark:bg-gray-800 shadow-xl rounded-xl p-5 z-50">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Tgl Mulai --}}
                            <div>
                                <p class="font-semibold mb-2">Tanggal Mulai</p>
                                <input type="date" x-model="start"
                                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                              border-gray-300 dark:border-gray-600">
                            </div>

                            {{-- Tgl Selesai --}}
                            <div>
                                <p class="font-semibold mb-2">Tanggal Selesai</p>
                                <input type="date" x-model="end"
                                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                              border-gray-300 dark:border-gray-600">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-4">
                            <button @click="openCalendar = false"
                                    class="px-4 py-2 border rounded-md">
                                Batal
                            </button>

                            <button
                                @click="
                                    if(start && end){
                                        const s = new Date(start);
                                        const e = new Date(end);
                                        duration = Math.floor((e - s) / (1000*60*60*24)) + 1;
                                        openCalendar = false;
                                    }
                                "
                                class="px-4 py-2 bg-gray-900 text-white rounded-md dark:bg-white dark:text-gray-900">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                <div>
                    <label class="font-medium">Lampiran</label>
                    <input type="file"
                           class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                  border-gray-300 dark:border-gray-600
                                  text-gray-800 dark:text-gray-100">
                </div>

                {{-- Durasi --}}
                <div class="md:col-span-2">
                    <label class="font-medium">Durasi :</label>
                    <p class="text-gray-600 dark:text-gray-300 mt-1" x-text="duration + ' Hari'"></p>
                </div>

                {{-- Tombol --}}
                <div class="md:col-span-2 flex justify-center gap-4 mt-4">
                    <button class="px-6 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900">
                        Submit
                    </button>

                    <button type="button"
                            @click="openModal = false"
                            class="px-6 py-2 rounded-md border border-gray-400 dark:border-gray-600
                                   text-gray-700 dark:text-gray-300">
                        Batal
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
@endsection

