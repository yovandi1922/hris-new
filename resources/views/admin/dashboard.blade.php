@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="p-8 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

  
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- Total Karyawan -->
    <div class="col-span-1 bg-gradient-to-b from-gray-700 to-gray-500 text-white p-6 rounded-3xl shadow-md border border-gray-600">
      <h3 class="text-lg font-semibold">Total Karyawan</h3>
      <p class="text-sm text-gray-300">Aktif saat ini</p>
      <p class="text-6xl font-bold mt-4">{{ number_format($totalKaryawan) }}</p>

      <div class="mt-6 flex gap-3">
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">✎ Edit</button>
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">＋ Tambah</button>
      </div>
    </div>

    <!-- Absensi Chart -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Absensi</h3>
      <canvas id="absensiChart" class="h-64"></canvas>
    </div>

    <!-- Notifikasi -->
<div class="col-span-1 row-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700">
  <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Notifikasi</h3>

  @if (!empty($notifikasi))
    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
      @foreach ($notifikasi as $n)
        <div class="flex items-center gap-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-2xl">
          <span class="w-10 h-10 flex items-center justify-center rounded-full bg-{{ $n['color'] }}-100 text-{{ $n['color'] }}-500 text-xl">
            {{ $n['icon'] }}
          </span>
          <div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
              {{ $n['judul'] }} 
              <span class="text-gray-400 text-xs ml-2">{{ $n['waktu'] }}</span>
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-300">{{ $n['pesan'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10">
      Tidak ada notifikasi saat ini.
    </div>
  @endif

  <button class="mt-5 w-full bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white py-2 rounded-xl transition">
    Lihat semua
  </button>
</div>


    <!-- Jam + Donut -->
    <div class="col-span-1 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700 flex flex-col items-center">
      <div class="text-5xl font-bold text-gray-800 dark:text-gray-100" id="liveClock">--:--</div>
      <p class="text-gray-500 dark:text-gray-300" id="liveDate">--</p>
      <div class="mt-6 w-56 h-56">
        <canvas id="absenDonut"></canvas>
      </div>
      <div class="flex gap-6 mt-4 text-sm text-gray-600 dark:text-gray-300">
        <span>⬛ Sudah absen</span>
        <span>⬜ Belum</span>
      </div>
    </div>

    <!-- Cuti Chart -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Cuti</h3>
      <canvas id="cutiChart" class="h-64"></canvas>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // === Grafik ===
  const labels = ['Sen','Sel','Rab','Kam','Jum'];
  const absensiData = @json($absensiHarian->values());
  const cutiData = @json($cuti->values());

  new Chart(document.getElementById('absensiChart'), {
    type: 'bar',
    data: {
      labels,
      datasets: [{ data: absensiData, backgroundColor: '#fde68a', borderRadius: 10 }]
    },
    options: { plugins: { legend: { display: false } } }
  });

  new Chart(document.getElementById('cutiChart'), {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Cuti', data: cutiData, backgroundColor: '#6b7280', borderRadius: 10 }
      ]
    },
    options: { plugins: { legend: { labels: { color: '#6b7280' } } } }
  });

  new Chart(document.getElementById('absenDonut'), {
    type: 'doughnut',
    data: {
      labels: ['Sudah Absen', 'Belum Absen'],
      datasets: [{
        data: [{{ $sudahAbsen }}, {{ $belumAbsen }}],
        backgroundColor: ['#374151','#d1d5db'],
        borderWidth: 0
      }]
    },
    options: { cutout: '70%', plugins: { legend: { display: false } } }
  });

  // === Waktu Real-Time (update tiap detik) ===
  function updateTime() {
    const now = new Date();
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const dayName = days[now.getDay()];
    const date = now.getDate();
    const monthName = months[now.getMonth()];

    document.getElementById('liveClock').textContent = `${hours}:${minutes}`;
    document.getElementById('liveDate').textContent = `${dayName}, ${date} ${monthName}`;
    document.getElementById('clock').textContent = `${hours}:${minutes}`;
  }

  updateTime();
  setInterval(updateTime, 1000);
});
</script>
@endsection
