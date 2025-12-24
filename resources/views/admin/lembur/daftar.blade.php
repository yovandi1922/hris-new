@extends('layouts.admin')

@section('title', 'Daftar Karyawan Lembur')

@section('content')

<div class="flex flex-col gap-4" x-data="{
    showModal: false,
    detail: {},
    openModal(lembur) {
        this.detail = lembur;
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
    }
}">
    <!-- Header & Search/Filter Bar sejajar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <h2 class="text-3xl font-bold text-white mb-0">
            <a href="{{ route('admin.lembur.index') }}" class="hover:underline">Lembur</a>
        </h2>
        <form method="GET" action="" class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Data Pengajuan Lembur" class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm text-black" />
            <button type="submit" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800"><i class="fa fa-search"></i></button>
            <button type="button" onclick="document.getElementById('filterSidebar').classList.remove('hidden')" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800 flex items-center gap-1"><i class="fa fa-filter"></i></button>
            @if(request('search') || request('status') || request('tanggal'))
                <a href="?" class="p-2 rounded-lg border border-gray-400 bg-gray-200 text-gray-700 hover:bg-gray-300 text-xs">Reset</a>
            @endif
        </form>
        <!-- Sidebar Filter -->
        <div id="filterSidebar" class="fixed top-0 right-0 h-full w-full sm:w-80 bg-white dark:bg-gray-900 shadow-lg z-50 hidden transition-all duration-300">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-bold text-lg dark:text-gray-100">Filter Lembur</h3>
                <button onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-2xl">&times;</button>
            </div>
            <form method="GET" action="" class="p-4 flex flex-col gap-4">
                <div>
                    <label class="font-semibold mb-1 block dark:text-gray-100">Status</label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="status[]" value="Disetujui" {{ collect(request('status'))->contains('Disetujui') ? 'checked' : '' }}>
                        <span class="text-sm dark:text-gray-100">Disetujui</span>
                    </label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="status[]" value="Menunggu" {{ collect(request('status'))->contains('Menunggu') ? 'checked' : '' }}>
                        <span class="text-sm dark:text-gray-100">Menunggu</span>
                    </label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="status[]" value="Ditolak" {{ collect(request('status'))->contains('Ditolak') ? 'checked' : '' }}>
                        <span class="text-sm dark:text-gray-100">Ditolak</span>
                    </label>
                </div>
                <div>
                    <label class="font-semibold mb-1 block dark:text-gray-100">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="border rounded px-2 py-1 w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="submit" class="flex-1 px-3 py-2 rounded bg-blue-600 text-white">Terapkan Filter</button>
                    <button type="button" onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="flex-1 px-3 py-2 rounded bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-gray-100">Batal</button>
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-lg p-4">
        <table class="min-w-full divide-y divide-gray-700 text-base text-white">
            <thead>
                <tr class="bg-gray-800">
                    <th class="px-4 py-3 text-left font-semibold">NIP</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Karyawan</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Mulai</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Selesai</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Jam</th>
                    <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Diproses Oleh</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($lemburs as $lembur)
                <tr class="bg-gray-900 hover:bg-gray-800 transition cursor-pointer" @click="openModal({
                    id: {{ $lembur->id }},
                    nama: '{{ addslashes($lembur->user->name ?? '-') }}',
                    nip: '{{ addslashes($lembur->user->nip ?? '-') }}',
                    tanggal: '{{ \Carbon\Carbon::parse($lembur->tanggal)->format('d M Y') }}',
                    jam_mulai: '{{ $lembur->jam_mulai }}',
                    jam_selesai: '{{ $lembur->jam_selesai }}',
                    total_jam: '{{
                        (function() use ($lembur) {
                            $mulai = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_mulai);
                            $selesai = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_selesai);
                            if ($selesai->lessThan($mulai)) $selesai->addDay();
                            $totalMenit = $mulai->diffInMinutes($selesai);
                            $jam = floor($totalMenit / 60);
                            $menit = $totalMenit % 60;
                            return ($jam > 0 ? $jam.' Jam' : '').($menit > 0 ? ' '.$menit.' Menit' : ($jam == 0 ? '0 Menit' : ''));
                        })()
                    }}',
                    keterangan: '{{ addslashes($lembur->keterangan) }}',
                    status: '{{ $lembur->status }}',
                    status_badge: `@if($lembur->status == 'Disetujui')bg-green-700@elseif($lembur->status == 'Ditolak')bg-red-700@else bg-yellow-600 @endif`,
                    bukti: @json($lembur->bukti_lampiran),
                    bukti_url: @json($lembur->bukti_lampiran ? asset('storage/'.$lembur->bukti_lampiran) : null)
                })">
                    <td class="px-4 py-3">{{ $lembur->user->nip ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $lembur->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($lembur->tanggal)->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $lembur->jam_mulai }}</td>
                    <td class="px-4 py-3">{{ $lembur->jam_selesai }}</td>
                    <td class="px-4 py-3">
                        @php
                            $mulai = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_mulai);
                            $selesai = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_selesai);
                            if ($selesai->lessThan($mulai)) {
                                $selesai->addDay();
                            }
                            $totalMenit = $mulai->diffInMinutes($selesai);
                            $jam = floor($totalMenit / 60);
                            $menit = $totalMenit % 60;
                        @endphp
                        {{ $jam > 0 ? $jam.' Jam' : '' }}{{ $menit > 0 ? ' '.$menit.' Menit' : ($jam == 0 ? '0 Menit' : '') }}
                    </td>
                    <td class="px-4 py-3">{{ $lembur->keterangan }}</td>
                    <td class="px-4 py-3">
                        @if($lembur->status == 'Disetujui')
                            <span class="px-3 py-1 rounded-full bg-green-700 text-white text-sm">Disetujui</span>
                        @elseif($lembur->status == 'Ditolak')
                            <span class="px-3 py-1 rounded-full bg-red-700 text-white text-sm">Ditolak</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-yellow-600 text-white text-sm">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($lembur->approvedBy)
                            {{ $lembur->approvedBy->name }}<br>
                            <span class="text-xs text-gray-400">{{ $lembur->approved_at ? \Carbon\Carbon::parse($lembur->approved_at)->format('d M Y H:i') : '' }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($lembur->status === 'Menunggu')
                            <div class="flex gap-2">
                                <form action="{{ route('admin.lembur.setujui', $lembur->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">✅ Setujui</button>
                                </form>
                                <form action="{{ route('admin.lembur.tolak', $lembur->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold">❌ Tolak</button>
                                </form>
                            </div>
                        @elseif($lembur->status === 'Disetujui' || $lembur->status === 'Ditolak')
                            <form action="{{ route('admin.lembur.batal', $lembur->id) }}" method="POST" onsubmit="return confirm('Batalkan persetujuan/tolak lembur ini?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-yellow-600 hover:bg-yellow-700 text-white font-semibold">🔄 Batalkan</button>
                            </form>
                        @else
                            <span class="px-4 py-2 rounded-lg bg-gray-700 text-white font-semibold">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-4">Belum ada pengajuan lembur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Modal Detail Lembur -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
        <div @click.away="closeModal" class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-lg p-6 relative">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Detail Lembur</h3>
            <div class="space-y-2 text-gray-800 dark:text-gray-100">
                <div><span class="font-semibold">Nama Karyawan:</span> <span x-text="detail.nama"></span></div>
                <div><span class="font-semibold">NIP:</span> <span x-text="detail.nip"></span></div>
                <div><span class="font-semibold">Tanggal Lembur:</span> <span x-text="detail.tanggal"></span></div>
                <div><span class="font-semibold">Jam Mulai:</span> <span x-text="detail.jam_mulai"></span></div>
                <div><span class="font-semibold">Jam Selesai:</span> <span x-text="detail.jam_selesai"></span></div>
                <div><span class="font-semibold">Total Jam:</span> <span x-text="detail.total_jam"></span></div>
                <div><span class="font-semibold">Keterangan:</span> <span x-text="detail.keterangan"></span></div>
                <div>
                    <span class="font-semibold">Status:</span>
                    <span :class="'px-3 py-1 rounded-full text-white text-sm ' + detail.status_badge" x-text="detail.status"></span>
                </div>
                <div>
                    <span class="font-semibold">Bukti Lampiran:</span>
                    <template x-if="detail.bukti_url">
                        <a :href="detail.bukti_url" target="_blank" class="text-blue-600 underline ml-2">Lihat Lampiran</a>
                    </template>
                    <template x-if="!detail.bukti_url">
                        <span class="ml-2 text-gray-400">Tidak ada</span>
                    </template>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <form :action="'/admin/lembur/setujui/' + detail.id" method="POST" x-show="detail.status === 'Menunggu'" @click.stop>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Setujui</button>
                </form>
                <form :action="'/admin/lembur/tolak/' + detail.id" method="POST" x-show="detail.status === 'Menunggu'" @click.stop>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold">Tolak</button>
                </form>
                <button @click="closeModal" class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-gray-900 font-semibold">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
