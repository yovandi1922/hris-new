@extends('layouts.admin')

@section('title', 'list karyawan')

@section('content')
<div class="flex flex-col gap-4">
    <!-- Breadcrumb -->
    

    <!-- Header and Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-2xl font-bold mb-2 sm:mb-0">Cuti dan Izin</h2>
        <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Data Pengajuan Cuti & Izin" class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm text-black" />
            <button type="submit" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"><i class="fa fa-search"></i></button>
            <button type="button" onclick="document.getElementById('filterSidebar').classList.remove('hidden')" class="p-2 rounded-lg border border-gray-900 bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200 flex items-center gap-1"><i class="fa fa-filter"></i> Filter</button>
            @if(request('search') || request('status') || request('jenis') || request('tanggal_mulai') || request('tanggal_selesai'))
                <a href="{{ route('admin.pengajuan.index') }}" class="p-2 rounded-lg border border-gray-400 bg-gray-200 text-gray-700 hover:bg-gray-300 text-xs">Reset</a>
            @endif
        </form>
        <!-- Sidebar Filter -->
        <div id="filterSidebar" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white dark:bg-gray-900 shadow-lg z-50 hidden transition-all duration-300">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-bold text-lg dark:text-gray-100">Filter Pengajuan</h3>
                <button onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-2xl">&times;</button>
            </div>
            <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="p-4 flex flex-col gap-4">
                <div>
                    <label class="font-semibold mb-1 block dark:text-gray-100">Jenis Cuti</label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="jenis[]" value="Cuti Pribadi" {{ collect(request('jenis'))->contains('Cuti Pribadi') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                        <span class="text-sm dark:text-gray-100">Cuti Pribadi</span>
                    </label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="jenis[]" value="Cuti Tahunan" {{ collect(request('jenis'))->contains('Cuti Tahunan') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                        <span class="text-sm dark:text-gray-100">Cuti Tahunan</span>
                    </label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="jenis[]" value="Izin Sakit" {{ collect(request('jenis'))->contains('Izin Sakit') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
                        <span class="text-sm dark:text-gray-100">Izin Sakit</span>
                    </label>
                </div>
                <div>
                    <label class="font-semibold mb-1 block dark:text-gray-100">Status</label>
                    <label class="flex items-center gap-2 mb-1 dark:text-gray-100">
                        <input type="checkbox" name="status[]" value="acc" {{ collect(request('status'))->contains('acc') ? 'checked' : '' }} class="dark:bg-gray-800 dark:border-gray-600">
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
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="border rounded px-2 py-1 w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                    <span class="mx-2 dark:text-gray-100">s/d</span>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="border rounded px-2 py-1 w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="submit" class="flex-1 px-3 py-2 rounded bg-blue-600 text-white">Terapkan Filter</button>
                    <button type="button" onclick="document.getElementById('filterSidebar').classList.add('hidden')" class="flex-1 px-3 py-2 rounded bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-gray-100">Batal</button>
                </div>
            </form>
        </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-black dark:text-gray-100 dark:bg-gray-900">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">NIP</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Karyawan</th>
                    <th class="px-4 py-3 text-left font-semibold">Jenis</th>
                    <th class="px-4 py-3 text-left font-semibold">Durasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-black dark:text-gray-100">
                @foreach($pengajuan as $p)
                <tr class="bg-white dark:bg-gray-800 cursor-pointer detail-row"
                    data-nama="{{ $p->user->name ?? '-' }}"
                    data-nip="{{ $p->user->nip ?? '-' }}"
                    data-jabatan="{{ $p->user->role == 'admin' ? 'Admin' : ($p->user->jabatan ?: 'Staf') }}"
                    data-id="{{ $p->id }}"
                    data-jenis="{{ $p->jenis }}"
                    data-tanggal-mulai="{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d F Y') }}"
                    data-tanggal-selesai="{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d F Y') }}"
                    data-durasi="{{ $p->durasi }} Hari"
                    data-keterangan="{{ $p->keterangan ?? '-' }}"
                    data-status="{{ $p->status == 'acc' ? 'Disetujui' : ($p->status == 'ditolak' ? 'Ditolak' : 'Menunggu') }}"
                    data-lampiran="{{ $p->bukti ? route('lampiran.download', ['filename' => basename($p->bukti)]) : '-' }}"
                    data-lampiran-nama="{{ $p->bukti_nama_asli ?? '' }}"
                >
                    <td class="px-4 py-2">{{ $p->user->nip ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->user->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->jenis }}</td>
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}<br>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $p->durasi }} Hari</span>
                    </td>
                    <td class="px-4 py-2">{{ $p->keterangan ?? '-' }}</td>
                    <td class="px-2 py-2">
                        @if($p->status == 'acc')
                            <span class="px-2 py-1 rounded-full bg-gray-900 text-white text-xs dark:bg-gray-100 dark:text-gray-900">Disetujui</span>
                        @elseif($p->status == 'ditolak')
                            <span class="px-2 py-1 rounded-full bg-gray-900 text-white text-xs dark:bg-gray-100 dark:text-gray-900">Ditolak</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-gray-900 text-white text-xs dark:bg-gray-100 dark:text-gray-900">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        @if($p->status == 'pending')
                            <form action="{{ route('admin.pengajuan.acc', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 px-3 py-1 rounded-full bg-gray-900 text-white hover:bg-gray-800 text-xs dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"><i class="fa fa-check-circle"></i> Setujui</button>
                            </form>
                            <form action="{{ route('admin.pengajuan.tolak', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 px-3 py-1 rounded-full bg-gray-900 text-white hover:bg-gray-800 text-xs dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"><i class="fa fa-times-circle"></i> Tolak</button>
                            </form>
                        @elseif($p->status == 'acc' || $p->status == 'ditolak')
                            <form action="{{ route('admin.pengajuan.batal', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 px-3 py-1 rounded-full bg-red-600 text-white hover:bg-red-700 text-xs"><i class="fa fa-undo"></i> Batalkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Cuti/Izin -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 w-full max-w-2xl shadow-lg relative text-white">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-2xl text-gray-400 hover:text-white">&times;</button>
        <h3 class="text-2xl font-semibold mb-6">Detail Pengajuan Cuti / Ijin</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 mb-6">
            <div><span class="text-gray-400">Nama</span><br><span id="modal-nama" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">NIP</span><br><span id="modal-nip" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Jabatan</span><br><span id="modal-jabatan" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Jenis Cuti / Izin</span><br><span id="modal-jenis" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Tanggal Mulai</span><br><span id="modal-tanggal-mulai" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Tanggal Selesai</span><br><span id="modal-tanggal-selesai" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Durasi</span><br><span id="modal-durasi" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Keterangan</span><br><span id="modal-keterangan" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Status</span><br><span id="modal-status" class="font-semibold text-lg"></span></div>
            <div><span class="text-gray-400">Lampiran</span><br><span id="modal-lampiran" class="font-semibold text-lg"></span></div>
        </div>
        <div class="flex gap-4 justify-center mt-4">
            <button id="btn-setujui" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold flex items-center gap-2"><i class="fa fa-check"></i> Setujui</button>
            <button id="btn-tolak" class="px-6 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold flex items-center gap-2"><i class="fa fa-times"></i> Tolak</button>
        </div>
    </div>
</div>

<script>
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.detail-row').forEach(function(row) {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            // Hindari klik pada tombol aksi di dalam baris
            if (e.target.closest('button, form, a')) return;
            document.getElementById('modal-nama').textContent = row.dataset.nama;
            document.getElementById('modal-nip').textContent = row.dataset.nip;
            document.getElementById('modal-jabatan').textContent = row.dataset.jabatan;
            document.getElementById('detailModal').setAttribute('data-id', row.dataset.id);
            document.getElementById('modal-jenis').textContent = row.dataset.jenis;
            document.getElementById('modal-tanggal-mulai').textContent = row.dataset['tanggalMulai'] || row.dataset['tanggal-mulai'];
            document.getElementById('modal-tanggal-selesai').textContent = row.dataset['tanggalSelesai'] || row.dataset['tanggal-selesai'];
            document.getElementById('modal-durasi').textContent = row.dataset.durasi;
            document.getElementById('modal-keterangan').textContent = row.dataset.keterangan;
            document.getElementById('modal-status').textContent = row.dataset.status;
            var lampiran = row.dataset.lampiran;
            var lampiranEl = document.getElementById('modal-lampiran');
            if (lampiran && lampiran !== '-') {
                var namaAsli = row.dataset.lampiranNama || '';
                var link = document.createElement('a');
                link.href = lampiran;
                link.className = 'underline text-blue-400';
                link.textContent = 'Download Lampiran';
                if (namaAsli) link.setAttribute('download', namaAsli);
                lampiranEl.innerHTML = '';
                lampiranEl.appendChild(link);
            } else {
                lampiranEl.textContent = '-';
            }
            document.getElementById('detailModal').classList.remove('hidden');
        });
    });
});
// Fungsi untuk handle tombol setujui dan tolak
document.getElementById('btn-setujui').onclick = function() {
    var id = document.getElementById('detailModal').getAttribute('data-id');
    if (!id) return;
    fetch('/admin/pengajuan/acc/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
    }).then(res => {
        if (res.ok) {
            location.reload();
        } else {
            alert('Gagal menyetujui pengajuan!');
        }
    });
};
document.getElementById('btn-tolak').onclick = function() {
    var id = document.getElementById('detailModal').getAttribute('data-id');
    if (!id) return;
    fetch('/admin/pengajuan/tolak/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
    }).then(res => {
        if (res.ok) {
            location.reload();
        } else {
            alert('Gagal menolak pengajuan!');
        }
    });
};
</script>
</div>
@endsection