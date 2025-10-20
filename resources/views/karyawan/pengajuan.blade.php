@extends('layouts.karyawan')

@section('title', 'Pengajuan')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
  <div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg text-white p-8 mb-8">
      <div class="absolute right-6 top-4 opacity-20 text-7xl font-extrabold select-none">📄</div>
      <h1 class="text-3xl font-bold mb-2">Pengajuan Karyawan</h1>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
      <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-6 border border-green-300 shadow-sm">
        {{ session('success') }}
      </div>
    @endif

    {{-- FORM PENGAJUAN --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-10">
      <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2">
        Buat Pengajuan Baru
      </h2>

      <form action="{{ route('karyawan.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">
          {{-- Tanggal --}}
          <div>
            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" required
              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
          </div>

          {{-- Jenis Pengajuan --}}
          <div>
            <label for="jenis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pengajuan</label>
            <select id="jenis" name="jenis" required onchange="toggleForm()"
              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
              <option value="">-- Pilih Jenis --</option>
              <option value="cuti">Cuti</option>
              <option value="lembur">Lembur</option>
              <option value="kasbon">Kasbon</option>
            </select>
          </div>
        </div>

        {{-- FORM KHUSUS --}}
        <div id="formCuti" class="hidden">
          <label for="bukti" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Upload Bukti (opsional)</label>
          <input type="file" name="bukti"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
        </div>

        <div id="formLembur" class="hidden">
          <label for="jam_lembur" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Jumlah Jam Lembur</label>
          <input type="number" id="jam_lembur" name="jam_lembur" min="1"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
        </div>

        <div id="formKasbon" class="hidden">
          <label for="nominal" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nominal Kasbon</label>
          <input type="number" id="nominal" name="nominal"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
        </div>

        {{-- Keterangan --}}
        <div>
          <label for="keterangan" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Keterangan</label>
          <textarea id="keterangan" name="keterangan" rows="3" placeholder="Tuliskan alasan atau detail pengajuan..."
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        {{-- Tombol --}}
        <div class="text-right">
          <button type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 transition-all shadow">
            Ajukan Sekarang
          </button>
        </div>
      </form>
    </div>

    {{-- DAFTAR PENGAJUAN --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-200 dark:border-gray-700">
      <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2">
        Daftar Pengajuan Saya
      </h2>

      <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
          <thead class="bg-blue-600 text-white dark:bg-gray-700">
            <tr>
              <th class="px-4 py-2 text-left">Tanggal</th>
              <th class="px-4 py-2 text-left">Jenis</th>
              <th class="px-4 py-2 text-left">Jam</th>
              <th class="px-4 py-2 text-left">Nominal</th>
              <th class="px-4 py-2 text-left">Keterangan</th>
              <th class="px-4 py-2 text-left">Bukti</th>
              <th class="px-4 py-2 text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pengajuan as $p)
              <tr class="text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-left">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-left">{{ ucfirst($p->jenis) }}</td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">{{ $p->jam_lembur ?? '-' }}</td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                  {{ $p->nominal ? 'Rp '.number_format($p->nominal,0,',','.') : '-' }}
                </td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-left">{{ $p->keterangan ?? '-' }}</td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                  @if($p->bukti)
                    <a href="{{ asset('storage/'.$p->bukti) }}" target="_blank" class="text-blue-600 underline hover:text-blue-800">Lihat</a>
                  @else
                    -
                  @endif
                </td>
                <td class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $p->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                       ($p->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
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

  </div>
</div>

<script>
function toggleForm() {
  const jenis = document.getElementById('jenis').value;
  ['formCuti','formLembur','formKasbon'].forEach(id => document.getElementById(id).classList.add('hidden'));
  if (jenis === 'cuti') document.getElementById('formCuti').classList.remove('hidden');
  if (jenis === 'lembur') document.getElementById('formLembur').classList.remove('hidden');
  if (jenis === 'kasbon') document.getElementById('formKasbon').classList.remove('hidden');
}
</script>
@endsection
