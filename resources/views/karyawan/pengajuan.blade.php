@extends('layouts.karyawan')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
  <h1 class="text-2xl font-bold mb-6">Pengajuan</h1>

  {{-- ALERT SUCCESS --}}
  @if(session('success'))
    <p class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</p>
  @endif

  {{-- FORM PENGAJUAN --}}
  <div class="bg-white shadow rounded p-4 mb-6">
    <h2 class="text-lg font-semibold mb-4">Buat Pengajuan Baru</h2>

    <form action="{{ route('karyawan.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf

      <div>
        <label for="tanggal" class="block font-medium">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label for="jenis" class="block font-medium">Jenis Pengajuan</label>
        <select id="jenis" name="jenis" class="w-full border rounded p-2" required onchange="toggleForm()">
          <option value="">-- Pilih Jenis --</option>
          <option value="cuti">Cuti</option>
          <option value="lembur">Lembur</option>
          <option value="kasbon">Kasbon</option>
        </select>
      </div>

      <div id="formCuti" class="hidden">
        <label for="bukti" class="block font-medium">Upload Bukti (opsional)</label>
        <input type="file" name="bukti" class="w-full border rounded p-2">
      </div>

      <div id="formLembur" class="hidden">
        <label for="jam_lembur" class="block font-medium">Jumlah Jam Lembur</label>
        <input type="number" id="jam_lembur" name="jam_lembur" min="1" class="w-full border rounded p-2">
      </div>

      <div id="formKasbon" class="hidden">
        <label for="nominal" class="block font-medium">Nominal Kasbon</label>
        <input type="number" id="nominal" name="nominal" class="w-full border rounded p-2">
      </div>

      <div>
        <label for="keterangan" class="block font-medium">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" class="w-full border rounded p-2"></textarea>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
        Ajukan
      </button>
    </form>
  </div>

  {{-- DAFTAR PENGAJUAN --}}
  <div class="overflow-x-auto">
    <h2 class="text-lg font-semibold mb-4">Daftar Pengajuan Saya</h2>
    <table class="min-w-full border border-gray-300">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 border">Tanggal</th>
          <th class="px-4 py-2 border">Jenis</th>
          <th class="px-4 py-2 border">Jam</th>
          <th class="px-4 py-2 border">Nominal</th>
          <th class="px-4 py-2 border">Keterangan</th>
          <th class="px-4 py-2 border">Bukti</th>
          <th class="px-4 py-2 border">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pengajuan as $p)
          <tr class="text-center hover:bg-gray-50">
            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
            <td class="px-4 py-2 border">{{ ucfirst($p->jenis) }}</td>
            <td class="px-4 py-2 border">{{ $p->jam_lembur ?? '-' }}</td>
            <td class="px-4 py-2 border">{{ $p->nominal ? 'Rp '.number_format($p->nominal,0,',','.') : '-' }}</td>
            <td class="px-4 py-2 border">{{ $p->keterangan ?? '-' }}</td>
            <td class="px-4 py-2 border">
              @if($p->bukti)
                <a href="{{ asset('storage/'.$p->bukti) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
              @else
                -
              @endif
            </td>
            <td class="px-4 py-2 border">
              <span class="px-2 py-1 rounded font-semibold
                {{ $p->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                   ($p->status == 'disetujui' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                {{ ucfirst($p->status) }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada pengajuan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
function toggleForm() {
  const jenis = document.getElementById('jenis').value;
  document.getElementById('formCuti').classList.add('hidden');
  document.getElementById('formLembur').classList.add('hidden');
  document.getElementById('formKasbon').classList.add('hidden');

  if (jenis === 'cuti') document.getElementById('formCuti').classList.remove('hidden');
  if (jenis === 'lembur') document.getElementById('formLembur').classList.remove('hidden');
  if (jenis === 'kasbon') document.getElementById('formKasbon').classList.remove('hidden');
}
</script>
@endsection
