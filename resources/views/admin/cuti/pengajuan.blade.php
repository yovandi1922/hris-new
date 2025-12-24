@extends('layouts.admin')

@section('title', 'Pengajuan')

@section('content')


<div class="mb-8">
    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-gray-100 mb-4">Pengajuan Cuti: {{ $karyawan->name }}</h1>
</div>

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Tanggal</th>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Jenis</th>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Durasi</th>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Status</th>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold">Keterangan</th>
                <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 dark:text-gray-200">
            @foreach($pengajuan as $p)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <td class="p-4 border-b border-gray-200 dark:border-gray-700">{{ $p->tanggal }}</td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700">{{ $p->jenis }}</td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700">{{ $p->durasi }} hari</td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700">
                    @if($p->status == 'acc')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Disetujui</span>
                    @elseif($p->status == 'ditolak')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">Ditolak</span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Menunggu</span>
                    @endif
                </td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700">{{ $p->keterangan }}</td>
                <td class="p-4 border-b border-gray-200 dark:border-gray-700 text-center">
                    @if($p->status == 'pending')
                        <form action="{{ route('admin.pengajuan.acc', $p->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs shadow">ACC</button>
                        </form>
                        <form action="{{ route('admin.pengajuan.tolak', $p->id) }}" method="POST" class="inline-block ml-1">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs shadow">Tolak</button>
                        </form>
                    @else
                        <span class="text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
