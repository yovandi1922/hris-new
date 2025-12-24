@extends('layouts.karyawan')

@section('title', 'Bon Gaji')

@section('content')
<div class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Pengajuan Bon Gaji</h1>
                <p class="text-gray-500 dark:text-gray-400">Lihat dan ajukan bon gaji anda di sini.</p>
            </div>
            <button id="btnTambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow transition">
                + Tambah Bon Gaji
            </button>
        </div>

        {{-- RINGKASAN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <p class="text-gray-500 dark:text-gray-400">Total Pengajuan</p>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $summary['total_pengajuan'] ?? 0 }}</h2>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <p class="text-gray-500 dark:text-gray-400">Jumlah Bon Diajukan</p>
                <h2 class="text-2xl font-semibold text-yellow-500">Rp {{ number_format($summary['jumlah_bon'] ?? 0,0,',','.') }}</h2>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <p class="text-gray-500 dark:text-gray-400">Disetujui</p>
                <h2 class="text-2xl font-semibold text-green-500">Rp {{ number_format($summary['disetujui'] ?? 0,0,',','.') }}</h2>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <p class="text-gray-500 dark:text-gray-400">Ditolak</p>
                <h2 class="text-2xl font-semibold text-red-500">Rp {{ number_format($summary['ditolak'] ?? 0,0,',','.') }}</h2>
            </div>
        </div>

        {{-- TABEL RIWAYAT BON GAJI --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($item->jumlah,0,',','.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->keterangan }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'disetujui')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-600 rounded-full">Disetujui</span>
                            @elseif($item->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-600 rounded-full">Pending</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-400">Belum ada pengajuan bon gaji.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- POP-UP FORM --}}
<div id="modalBon" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Ajukan Bon Gaji</h2>

        <form method="POST" action="{{ route('karyawan.bon.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal</label>
                    <input name="jumlah" type="number" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nominal" required min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                    <textarea name="keterangan" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Tuliskan keterangan"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="btnBatal" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Kirim</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT MODAL --}}
<script>
document.getElementById('btnTambah').addEventListener('click', function() {
    document.getElementById('modalBon').classList.remove('hidden');
});
document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('modalBon').classList.add('hidden');
});
</script>
@endsection
