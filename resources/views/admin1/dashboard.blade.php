@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-8 min-h-screen font-sans bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- Total Karyawan -->
    <div class="col-span-1 bg-gradient-to-b from-gray-700 to-gray-500 text-white p-6 rounded-3xl shadow-md border border-gray-600">
      <h3 class="text-lg font-semibold">Total Karyawan</h3>
      <p class="text-sm text-gray-300">Aktif saat ini</p>
      <p class="text-6xl font-bold mt-4">1.482</p>
      <div class="mt-6 flex gap-3">
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">✎ Edit Karyawan</button>
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">＋ Tambah</button>
      </div>
    </div>

    <!-- Absensi Chart -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700 transition">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Absensi</h3>
      <canvas id="absensiChart" class="h-64"></canvas>
    </div>

    <!-- Notifikasi -->
    <div class="col-span-1 row-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700 transition">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Notifikasi</h3>
      <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">

        <!-- item -->
        <div class="flex items-center gap-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-2xl">
          <span class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500 text-xl">✖</span>
          <div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
              Absen <span class="text-gray-400 text-xs ml-2">8:20 AM</span>
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-300">5 Karyawan belum melakukan clock-in hari ini</p>
          </div>
        </div>

        <!-- item -->
        <div class="flex items-center gap-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-2xl">
          <span class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100 text-pink-500 text-xl">👤</span>
          <div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
              Karyawan <span class="text-gray-400 text-xs ml-2">5:07 AM</span>
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-300">Data karyawan baru ditambahkan</p>
          </div>
        </div>

        <!-- item -->
        <div class="flex items-center gap-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-2xl">
          <span class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 text-xl">💰</span>
          <div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
              Penggajian <span class="text-gray-400 text-xs ml-2">12:00 PM</span>
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-300">Proses payroll bulan September belum dilakukan</p>
          </div>
        </div>

      </div>
      <button class="mt-5 w-full bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white py-2 rounded-xl transition">Lihat semua</button>
    </div>

    <!-- Jam + Donut -->
    <div class="col-span-1 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700 flex flex-col items-center transition">
      <div class="text-5xl font-bold text-gray-800 dark:text-gray-100">9:14</div>
      <p class="text-gray-500 dark:text-gray-300">Wed, September 10</p>
      <div class="mt-6 w-56 h-56">
        <canvas id="absenDonut"></canvas>
      </div>
      <div class="flex gap-6 mt-4 text-sm text-gray-600 dark:text-gray-300">
        <span>⬛ Sudah absen</span>
        <span>⬜ Belum absen</span>
      </div>
    </div>

    <!-- Cuti Chart -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-md border border-gray-200 dark:border-gray-700 transition">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Cuti</h3>
      <canvas id="cutiChart" class="h-64"></canvas>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  // Absensi Chart
  new Chart(document.getElementById('absensiChart'), {
    type: 'bar',
    data: {
      labels: ['Sen','Sel','Rab','Kam','Jum'],
      datasets: [{
        data: [1400,1380,1390,1400,1200],
        backgroundColor: '#fde68a',
        borderRadius: 10
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 200 } }
      }
    }
  });

  // Cuti Chart
  new Chart(document.getElementById('cutiChart'), {
    type: 'bar',
    data: {
      labels: ['Sen','Sel','Rab','Kam','Jum'],
      datasets: [
        { label: 'Cuti', data: [50,60,80,55,30], backgroundColor: '#6b7280', borderRadius: 10 },
        { label: 'Pengajuan', data: [20,25,30,20,15], backgroundColor: '#d1d5db', borderRadius: 10 }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#6b7280' } } },
      scales: { 
        y: { beginAtZero: true, ticks: { color: '#6b7280' } },
        x: { ticks: { color: '#6b7280' } }
      }
    }
  });

  // Donut Absen
  new Chart(document.getElementById('absenDonut'), {
    type: 'doughnut',
    data: {
      labels: ['Sudah Absen','Belum Absen'],
      datasets: [{
        data: [1200,282],
        backgroundColor: ['#374151','#d1d5db'],
        borderWidth: 0
      }]
    },
    options: {
      cutout: '70%', 
      plugins: { legend: { display: false } }
    }
  });
</script>
@endsection
