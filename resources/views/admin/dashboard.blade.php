@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-8 min-h-screen bg-gray-100 dark:bg-[#020617] transition-colors duration-300">

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- Total Karyawan -->
    <div class="col-span-1 
                bg-gradient-to-b from-gray-700 to-gray-500 
                dark:from-[#020617] dark:to-[#111827]
                text-white p-6 rounded-3xl shadow-md border border-gray-600 dark:border-gray-800">
      <h3 class="text-lg font-semibold">Total Karyawan</h3>
      <p class="text-sm text-gray-300">Aktif saat ini</p>
      <p class="text-6xl font-bold mt-4">1.482</p>
      <div class="mt-6 flex gap-3">
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">
          ✎ Edit
        </button>
        <button class="flex-1 py-2 text-sm bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">
          ＋ Tambah
        </button>
      </div>
    </div>

    <!-- Absensi Chart -->
    <div class="col-span-2 
                bg-white dark:bg-[#020617]
                rounded-3xl p-6 shadow-md 
                border border-gray-200 dark:border-gray-800">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
        Absensi
      </h3>
      <canvas id="absensiChart" class="h-64"></canvas>
    </div>

    <!-- ================= NOTIFIKASI ================= -->
    <div class="row-span-2 bg-white dark:bg-[#020617] rounded-3xl p-6 shadow border border-gray-200 dark:border-gray-800">

      <!-- Search -->
      <div class="mb-4">
        <input type="text" id="searchNotif"
               placeholder="Cari notifikasi..."
               class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                      bg-gray-100 dark:bg-[#111827]
                      text-gray-800 dark:text-gray-100
                      focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      </div>

      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
        Notifikasi
      </h3>

      <div id="notifList" class="space-y-3 max-h-[500px] overflow-y-auto">

        <!-- ITEM -->
        <div class="notif-item">
          <div class="flex gap-3 p-3 bg-gray-100 dark:bg-[#111827] rounded-2xl">
            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500">✖</span>
            <div>
              <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Absen</p>
              <p class="text-xs text-gray-600 dark:text-gray-300">
                5 Karyawan belum clock-in
              </p>
            </div>
          </div>
        </div>

        <div class="notif-item">
          <div class="flex gap-3 p-3 bg-gray-100 dark:bg-[#111827] rounded-2xl">
            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100 text-pink-500">👤</span>
            <div>
              <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Karyawan</p>
              <p class="text-xs text-gray-600 dark:text-gray-300">
                Data baru ditambahkan
              </p>
            </div>
          </div>
        </div>

        <div class="notif-item">
          <div class="flex gap-3 p-3 bg-gray-100 dark:bg-[#111827] rounded-2xl">
            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600">💰</span>
            <div>
              <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Payroll</p>
              <p class="text-xs text-gray-600 dark:text-gray-300">
                Proses payroll belum dilakukan
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Jam + Donut -->
    <div class="col-span-1 
                bg-white dark:bg-[#020617]
                rounded-3xl p-6 shadow-md 
                border border-gray-200 dark:border-gray-800
                flex flex-col items-center">
      <div class="text-5xl font-bold text-gray-800 dark:text-gray-100">
        9:14
      </div>
      <p class="text-gray-500 dark:text-gray-400">
        Sab, Oktober 18
      </p>

      <div class="mt-6 w-56 h-56">
        <canvas id="absenDonut"></canvas>
      </div>

      <div class="flex gap-6 mt-4 text-sm text-gray-600 dark:text-gray-400">
        <span>⬛ Sudah absen</span>
        <span>⬜ Belum</span>
      </div>
    </div>

    <!-- Cuti Chart -->
    <div class="col-span-2 
                bg-white dark:bg-[#020617]
                rounded-3xl p-6 shadow-md 
                border border-gray-200 dark:border-gray-800">
      <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
        Cuti
      </h3>
      <canvas id="cutiChart" class="h-64"></canvas>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ================= SEARCH NOTIFIKASI ================= */
  const searchInput = document.getElementById('searchNotif');
  const notifItems = document.querySelectorAll('.notif-item');

  searchInput.addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();

    notifItems.forEach(item => {
      const text = item.innerText.toLowerCase();
      item.style.display = text.includes(keyword) ? 'block' : 'none';
    });
  });

  /* ================= CHART ================= */
  const isDark = document.documentElement.classList.contains('dark');
  const textColor = isDark ? '#e5e7eb' : '#374151';
  const gridColor = isDark ? '#1f2937' : '#e5e7eb';

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
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: textColor }, grid: { color: gridColor }},
        y: { ticks: { color: textColor }, grid: { color: gridColor }}
      }
    }
  });

  new Chart(document.getElementById('cutiChart'), {
    type: 'bar',
    data: {
      labels: ['Sen','Sel','Rab','Kam','Jum'],
      datasets: [
        { label: 'Cuti', data: [50,60,80,55,30], backgroundColor: '#334155', borderRadius: 10 },
        { label: 'Pengajuan', data: [20,25,30,20,15], backgroundColor: '#64748b', borderRadius: 10 }
      ]
    },
    options: {
      plugins: { legend: { labels: { color: textColor } } },
      scales: {
        x: { ticks: { color: textColor }, grid: { color: gridColor }},
        y: { ticks: { color: textColor }, grid: { color: gridColor }}
      }
    }
  });

  new Chart(document.getElementById('absenDonut'), {
    type: 'doughnut',
    data: {
      labels: ['Sudah Absen','Belum Absen'],
      datasets: [{
        data: [1200,282],
        backgroundColor: isDark ? ['#020617','#334155'] : ['#374151','#d1d5db'],
        borderColor: '#ffffff',
        borderWidth: 2
      }]
    },
    options: {
      cutout: '70%',
      plugins: { legend: { display: false } }
    }
  });

});
</script>
@endsection
