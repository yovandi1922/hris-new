@extends('layouts.karyawan')

@section('title', 'Pengajuan - Cuti & Izin')

@section('content')

<div x-data="{ openModal: false, start:'', end:'', duration:0 }" class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">


<h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Cuti dan Izin</h1>

{{-- Tombol Ajukan --}}
<div class="mb-8">
    <button @click="openModal = true"
       class="px-5 py-2 rounded-full bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900
              shadow hover:opacity-80 transition">
        Ajukan Cuti/Izin
    </button>
</div>

{{-- Tabel Riwayat Cuti --}}
<div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-0 ring-1 ring-gray-200 dark:ring-gray-700 overflow-x-auto">
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
            @foreach($pengajuan ?? [] as $p)
                <tr>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                        @if($p->tanggal_selesai)
                            - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $p->jenis }}</td>
                    <td class="px-6 py-4">{{ $p->durasi ?? 1 }} Hari</td>
                    <td class="px-6 py-4 font-semibold
                        {{ $p->status == 'acc' ? 'text-green-600 dark:text-green-400' : ($p->status == 'ditolak' ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400') }}">
                        {{ ucfirst($p->status) }}
                    </td>
                    <td class="px-6 py-4">{{ $p->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
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

        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data"
              class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            {{-- Jenis Pengajuan --}}
            <div>
                <label class="font-medium">Jenis Pengajuan<span class="text-red-500">*</span></label>
                <select name="jenis" required
                        class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                               border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
                    <option value="">Pilih Pengajuan</option>
                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                    <option value="Cuti Pribadi">Cuti Pribadi</option>
                    <option value="Izin Sakit">Izin Sakit</option>
                    <option value="Izin Lainnya">Izin Lainnya</option>
                </select>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="font-medium">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100"
                          placeholder="Tulis keterangan"></textarea>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="font-medium">Tanggal Mulai<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" x-model="start" required
                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
            </div>

            <div>
                <label class="font-medium">Tanggal Selesai<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" x-model="end" required
                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
            </div>

            {{-- Durasi (hidden) --}}
            <input type="hidden" name="durasi" :value="duration">

            {{-- Lampiran --}}
            <div class="md:col-span-2">
                <label class="font-medium">Lampiran</label>
                <input type="file" name="bukti"
                       class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100">
            </div>

            {{-- Hitung Durasi --}}
            <div class="md:col-span-2 mt-2">
                <p class="text-gray-600 dark:text-gray-300">
                    Durasi: <span x-text="start && end ? Math.floor((new Date(end) - new Date(start)) / (1000*60*60*24) + 1) : 0"></span> Hari
                </p>
            </div>

            {{-- Tombol --}}
            <div class="md:col-span-2 flex justify-center gap-4 mt-4">
                <button type="submit"
                        class="px-6 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900">
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

{{-- Alpine untuk durasi --}}

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cutiForm', () => ({
        start: '',
        end: '',
        get duration() {
            if(this.start && this.end){
                const s = new Date(this.start);
                const e = new Date(this.end);
                return Math.floor((e - s)/(1000*60*60*24)) + 1;
            }
            return 0;
        }
    }));
});
</script>

@endsection
