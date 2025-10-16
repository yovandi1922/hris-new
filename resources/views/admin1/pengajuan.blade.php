@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Pengajuan</h1>

    @if(session('success'))
        <p class="bg-green-100 text-green-700 p-2 rounded mb-3">{{ session('success') }}</p>
    @endif

    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border">Nama</th>
            <th class="px-4 py-2 border">Tanggal</th>
            <th class="px-4 py-2 border">Jenis</th>
            <th class="px-4 py-2 border">Jam Lembur</th>
            <th class="px-4 py-2 border">Nominal</th>
            <th class="px-4 py-2 border">Keterangan</th>
            <th class="px-4 py-2 border">Bukti</th>
            <th class="px-4 py-2 border">Status</th>
            <th class="px-4 py-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengajuan as $p)
            <tr class="hover:bg-gray-50 text-center">
              <td class="px-4 py-2 border">{{ $p->user->name }}</td>
              <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
              <td class="px-4 py-2 border">{{ ucfirst($p->jenis) }}</td>
              <td class="px-4 py-2 border">{{ $p->jam_lembur ?? '-' }}</td>
              <td class="px-4 py-2 border">{{ $p->nominal ? 'Rp '.number_format($p->nominal,0,',','.') : '-' }}</td>
              <td class="px-4 py-2 border">{{ $p->keterangan ?? '-' }}</td>
              <td class="px-4 py-2 border">
                @if($p->bukti)
                  <button onclick="showModal('{{ asset('storage/'.$p->bukti) }}')" class="text-blue-600 underline">Lihat Bukti</button>
                @else
                  -
                @endif
              </td>
              <td class="px-4 py-2 border">
                <span class="px-2 py-1 rounded text-white {{ $p->status == 'pending' ? 'bg-yellow-500' : ($p->status == 'disetujui' ? 'bg-green-600' : 'bg-red-600') }}">
                  {{ ucfirst($p->status) }}
                </span>
              </td>
              <td class="px-4 py-2 border">
                <form action="{{ route('pengajuan.updateStatus', $p->id) }}" method="POST">
                  @csrf
                  <select name="status" onchange="this.form.submit()" class="border p-1 rounded">
                    <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ $p->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                  </select>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-4 py-6 text-center text-gray-500">Belum ada pengajuan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
</div>

{{-- modal --}}
<div id="modalBukti" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-4 rounded shadow-lg max-w-2xl w-full">
    <img id="buktiImage" src="" class="w-full h-auto">
    <div class="text-right mt-3">
      <button onclick="closeModal()" class="bg-gray-700 text-white px-3 py-1 rounded">Tutup</button>
    </div>
  </div>
</div>

<script>
function showModal(src){
  document.getElementById('buktiImage').src = src;
  document.getElementById('modalBukti').classList.remove('hidden');
  document.getElementById('modalBukti').classList.add('flex');
}
function closeModal(){
  document.getElementById('modalBukti').classList.add('hidden');
}
</script>
@endsection
