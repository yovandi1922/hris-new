@extends('layouts.admin')

@section('title', 'Bon Gaji')

@section('content')
<div class="p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Bon Gaji</h1>
                <p class="text-gray-500 dark:text-gray-400">Daftar pengajuan bon gaji karyawan.</p>
            </div>
            <form method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama/NIP/Keterangan" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                <button type="submit" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"><i class="fa fa-search"></i></button>
                <button type="button" onclick="document.getElementById('filterSidebar').classList.remove('hidden')" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200 flex items-center gap-1"><i class="fa fa-filter"></i> Filter</button>
                @if(request('q') || request('status') || request('tanggal_mulai') || request('tanggal_selesai'))
                    <a href="{{ route('admin.bon.index') }}" class="p-2 rounded-lg border border-gray-400 bg-gray-200 text-gray-700 hover:bg-gray-300 text-xs">Reset</a>
                @endif
            </form>
            <!-- Sidebar Filter -->
            <div id="filterSidebar" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white dark:bg-gray-900 shadow-lg z-50 hidden transition-all duration-300">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="font-bold text-lg dark:text-gray-100">Filter Bon Gaji</h3>
                    <button onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-2xl">&times;</button>
                </div>
                <form method="GET" class="p-4 flex flex-col gap-4">
                    <div>
                        <label class="font-semibold mb-1 block dark:text-gray-100">Status</label>
                        <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                            <input type="checkbox" name="status[]" value="disetujui" {{ collect(request('status'))->contains('disetujui') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                            <span class="text-sm dark:text-gray-100">Disetujui</span>
                        </label>
                        <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                            <input type="checkbox" name="status[]" value="pending" {{ collect(request('status'))->contains('pending') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                            <span class="text-sm dark:text-gray-100">Menunggu</span>
                        </label>
                        <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                            <input type="checkbox" name="status[]" value="ditolak" {{ collect(request('status'))->contains('ditolak') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                            <span class="text-sm dark:text-gray-100">Ditolak</span>
                        </label>
                    </div>
                    <div>
                        <label class="font-semibold mb-1 block dark:text-gray-100">Tanggal Pengajuan</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="border rounded px-2 py-1 w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="flex-1 px-3 py-2 rounded bg-blue-600 text-white">Terapkan Filter</button>
                        <button type="button" onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="flex-1 px-3 py-2 rounded bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-gray-100">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nominal Pengajuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($list as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->user->nip ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format($item->jumlah,0,',','.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->keterangan }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-600 rounded-full">Menunggu</span>
                            @elseif($item->status == 'disetujui')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-600 rounded-full">Disetujui</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'pending')
                                <form action="{{ route('admin.bon.approve', $item->id) }}" method="POST" class="inline-block mr-1">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold">Setujui</button>
                                </form>
                                <form action="{{ route('admin.bon.reject', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-semibold">Tolak</button>
                                </form>
                            @elseif($item->status == 'disetujui' || $item->status == 'ditolak')
                                <form action="{{ route('admin.bon.batal', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs font-semibold">Batalkan</button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-400">Belum ada pengajuan bon gaji.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
