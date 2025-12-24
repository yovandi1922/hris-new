@extends('layouts.karyawan')

@section('title', 'Lembur')

@section('content')
<div class="p-6 min-h-screen"
    x-data="{
        openLembur:false,
        startTime:'00:00',
        endTime:'00:00',
        duration:'0 Jam',
        hitung() {
            if(!this.startTime || !this.endTime) return;
            let [h1,m1] = this.startTime.split(':').map(Number);
            let [h2,m2] = this.endTime.split(':').map(Number);
            let start = h1*60 + m1;
            let end   = h2*60 + m2;
            let diff = end - start;
            if(diff < 0) diff += 24*60;
            let j = Math.floor(diff/60);
            let m = diff % 60;
            this.duration = m === 0 ? `${j} Jam` : `${j} Jam ${m} Menit`;
        }
    }">

    <div class="max-w-6xl mx-auto space-y-10">

        {{-- Breadcrumb --}}
        <div class="text-right text-gray-600 dark:text-gray-300 text-sm">
            Dashboard / Pengajuan / <span class="font-semibold">Lembur</span>
        </div>

        {{-- Judul --}}
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Lembur</h1>

        {{-- RINGKASAN --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-8
                    border border-gray-200 dark:border-gray-700">

            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-6">
                Ringkasan Lembur
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Total Lembur</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalJam }}</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jam</span>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Total Pengajuan</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalPengajuan }}</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Kali</span>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Disetujui</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalDisetujui }}</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Kali</span>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400">Ditolak</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalDitolak }}</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Kali</span>
                </div>

            </div>

            <div class="mt-8 flex justify-center">
                <button @click="openLembur = true"
                    class="px-6 py-2 rounded-full border border-gray-400 dark:border-gray-600
                           text-gray-600 dark:text-gray-200
                           hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Ajukan Lembur
                </button>
            </div>

        </div>

        {{-- RIWAYAT --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-8 border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Riwayat Lembur</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <th class="py-3 px-4 font-semibold">Tanggal</th>
                            <th class="py-3 px-4 font-semibold">Jam Mulai</th>
                            <th class="py-3 px-4 font-semibold">Jam Selesai</th>
                            <th class="py-3 px-4 font-semibold">Total Jam</th>
                            <th class="py-3 px-4 font-semibold">Keterangan</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @forelse($riwayatLembur as $item)
                        <tr class="border-b border-gray-300 dark:border-gray-700">
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="py-3 px-4">{{ $item->jam_mulai }}</td>
                            <td class="py-3 px-4">{{ $item->jam_selesai }}</td>
                            <td class="py-3 px-4">{{ \App\Helpers\LemburHelper::hitungJam($item->jam_mulai, $item->jam_selesai) }}</td>
                            <td class="py-3 px-4">{{ $item->keterangan }}</td>
                            <td class="py-3 px-4">
                                @if($item->status == 'Disetujui')
                                    <span class="px-3 py-1 rounded-full bg-green-700 text-white text-sm">Disetujui</span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="px-3 py-1 rounded-full bg-red-700 text-white text-sm">Ditolak</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-yellow-600 text-white text-sm">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-3 px-4 text-center">Belum ada pengajuan lembur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL --}}
    <div x-show="openLembur"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
         x-transition.opacity>

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 w-full max-w-3xl"
             x-transition.scale>

            <h2 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-100 mb-8">
                Form Pengajuan Lembur
            </h2>

            <form method="POST" action="{{ route('lembur.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Tanggal --}}
                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Tanggal Lembur<span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal"
                            class="w-full px-3 py-2 border rounded-lg
                                   bg-white dark:bg-gray-900
                                   border-gray-300 dark:border-gray-600
                                   text-gray-800 dark:text-gray-100">
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Keterangan<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="keterangan"
                            class="w-full px-3 py-2 border rounded-lg
                                   bg-white dark:bg-gray-900
                                   border-gray-300 dark:border-gray-600
                                   text-gray-800 dark:text-gray-100">
                    </div>

                </div>

                {{-- Jam --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Jam Lembur<span class="text-red-500">*</span>
                        </label>

                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <p class="text-sm mb-1">Mulai</p>
                                <input type="time" name="jam_mulai" x-model="startTime" @change="hitung()"
                                   class="w-full px-3 py-2 border rounded-lg
                                          bg-white dark:bg-gray-900
                                          border-gray-300 dark:border-gray-600
                                          text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="flex-1">
                                <p class="text-sm mb-1">Selesai</p>
                                <input type="time" name="jam_selesai" x-model="endTime" @change="hitung()"
                                   class="w-full px-3 py-2 border rounded-lg
                                          bg-white dark:bg-gray-900
                                          border-gray-300 dark:border-gray-600
                                          text-gray-800 dark:text-gray-100">
                            </div>
                        </div>

                        <p class="mt-2 text-gray-700 dark:text-gray-300">
                            Durasi : <span x-text="duration" class="font-semibold"></span>
                        </p>
                    </div>

                    {{-- Bukti --}}
                    <div>
                        <label class="block font-medium mb-1 text-gray-700 dark:text-gray-200">
                            Bukti Kegiatan
                        </label>
                        <input type="file" name="bukti"
                            class="w-full px-3 py-2 border rounded-lg
                                   bg-gray-100 dark:bg-gray-900
                                   border-gray-300 dark:border-gray-600
                                   text-gray-800 dark:text-gray-100">
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="flex justify-center gap-4 pt-4">
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-gray-800 dark:bg-gray-200
                               text-white dark:text-gray-900
                               hover:bg-gray-700 dark:hover:bg-gray-300 transition">
                        Submit
                    </button>

                    <button type="button" @click="openLembur=false"
                        class="px-6 py-2 rounded-lg border border-gray-400 dark:border-gray-600
                               hover:bg-gray-100 dark:hover:bg-gray-700
                               text-gray-800 dark:text-gray-200 transition">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
