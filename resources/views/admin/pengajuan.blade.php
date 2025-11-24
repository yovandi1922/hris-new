@extends('layouts.karyawan')

@section('title', 'Pengajuan - Cuti & Izin')

@section('content')

<div class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

```
<h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Cuti dan Izin</h1>

{{-- Tombol Ajukan --}}
<button @click="document.getElementById('modalForm').classList.remove('hidden')"
        class="px-5 py-2 rounded-full bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900
               shadow hover:opacity-80 transition mb-6">
    Ajukan Cuti/Izin
</button>

{{-- Modal Form --}}
<div id="modalForm" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-40" 
         onclick="this.parentElement.classList.add('hidden')"></div>

    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-3xl p-8 z-50">
        <h2 class="text-xl font-semibold mb-6 text-center text-gray-800 dark:text-gray-100">
            Form Pengajuan Cuti/Izin
        </h2>

        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            {{-- Jenis Pengajuan --}}
            <div>
                <label class="font-medium">Jenis Pengajuan<span class="text-red-500">*</span></label>
                <select name="jenis" required
                        class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                               border-gray-300 dark:border-gray-600
                               text-gray-800 dark:text-gray-100">
                    <option value="">Pilih Pengajuan</option>
                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                    <option value="Cuti Pribadi">Cuti Pribadi</option>
                    <option value="Izin Sakit">Izin Sakit</option>
                    <option value="Izin Lainnya">Izin Lainnya</option>
                </select>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="font-medium">Keterangan<span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="3" required
                          class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                                 border-gray-300 dark:border-gray-600
                                 text-gray-800 dark:text-gray-100"
                          placeholder="Tulis keterangan"></textarea>
            </div>

            {{-- Tanggal Mulai --}}
            <div>
                <label class="font-medium">Tanggal Mulai<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" required
                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600
                              text-gray-800 dark:text-gray-100">
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label class="font-medium">Tanggal Selesai<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" required
                       class="w-full px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600
                              text-gray-800 dark:text-gray-100">
            </div>

            {{-- Lampiran --}}
            <div>
                <label class="font-medium">Lampiran</label>
                <input type="file" name="bukti"
                       class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-50 dark:bg-gray-700
                              border-gray-300 dark:border-gray-600
                              text-gray-800 dark:text-gray-100">
            </div>

            {{-- Tombol --}}
            <div class="md:col-span-2 flex justify-center gap-4 mt-4">
                <button type="submit" 
                        class="px-6 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900">
                    Submit
                </button>

                <button type="button"
                        onclick="document.getElementById('modalForm').classList.add('hidden')"
                        class="px-6 py-2 rounded-md border border-gray-400 dark:border-gray-600
                               text-gray-700 dark:text-gray-300">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Riwayat Cuti --}}
<div class="mt-10 bg-white dark:bg-gray-800 shadow-md rounded-2xl p-0 ring-1 ring-gray-200 dark:ring-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Cuti</h2>
    </div>

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
                @foreach($pengajuan as $p)
                <tr>
                    <td class="px-6 py-4">{{ $p->tanggal_mulai }} - {{ $p->tanggal_selesai }}</td>
                    <td class="px-6 py-4">{{ $p->jenis }}</td>
                    <td class="px-6 py-4">{{ $p->durasi }} Hari</td>
                    <td class="px-6 py-4">
                        @if($p->status == 'acc')
                            <span class="text-green-600 dark:text-green-400 font-semibold">Disetujui</span>
                        @elseif($p->status == 'ditolak')
                            <span class="text-red-600 dark:text-red-400 font-semibold">Ditolak</span>
                        @else
                            <span class="text-yellow-600 dark:text-yellow-400 font-semibold">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $p->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

</div>
@endsection
