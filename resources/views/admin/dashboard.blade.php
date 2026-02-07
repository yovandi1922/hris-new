@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="p-1.5 sm:p-2 md:p-3 lg:p-6 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300 overflow-x-auto">

  <!-- Header -->
  <div class="mb-2 sm:mb-3 md:mb-4 lg:mb-6">
    <h1 class="text-base sm:text-lg md:text-2xl lg:text-4xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-1">Selamat datang di HRMS Paradise Corp</p>
  </div>

  <!-- Main Grid Layout - Desktop Layout Fixed for All Screens -->
  <div class="grid grid-cols-4 gap-1.5 sm:gap-2 md:gap-3 lg:gap-4 auto-rows-max min-w-max" style="width: fit-content; min-width: 100%;">
    <!-- Note: Grid stays 4 cols on all screens, elements scale down proportionally -->

    <!-- Total Karyawan (1 col, 1 row) -->
    <div class="col-span-1 bg-gradient-to-b from-gray-700 to-gray-600 text-white p-1.5 sm:p-2 md:p-3 lg:p-6 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl shadow-md hover:shadow-lg transition-shadow flex flex-col justify-center" style="min-height: 80px;">
      <h3 class="text-xs sm:text-xs md:text-sm lg:text-base font-semibold text-gray-200 mb-1">Total Karyawan</h3>
      <p class="text-lg sm:text-xl md:text-2xl lg:text-5xl font-bold truncate">{{ number_format($totalKaryawan) }}</p>
    </div>

    <!-- Absensi Chart (2 cols, 1 row) -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl p-1.5 sm:p-2 md:p-3 lg:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col">
      <h3 class="text-xs sm:text-xs md:text-sm lg:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100 flex-shrink-0">Absensi</h3>
      <div class="w-full flex-1 min-h-20 sm:min-h-24 md:min-h-32 lg:min-h-56">
        <canvas id="absensiChart"></canvas>
      </div>
    </div>

    <!-- Notifikasi (1 col, 1 row) -->
    <div class="col-span-1 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl p-1.5 sm:p-2 md:p-3 lg:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col" style="min-height: 180px;">
      <h3 class="text-xs sm:text-xs md:text-sm lg:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100 flex-shrink-0">Notifikasi</h3>

      @if (!empty($notifikasi))
        <div class="flex-1 space-y-0.5 overflow-y-auto pr-1">
          @foreach ($notifikasi as $n)
            <div class="flex items-start gap-1 p-1 sm:p-1.5 bg-gray-100 dark:bg-gray-700 rounded-sm text-xs">
              <span class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center rounded-full bg-{{ $n['color'] }}-100 dark:bg-{{ $n['color'] }}-900 text-{{ $n['color'] }}-600 dark:text-{{ $n['color'] }}-300 flex-shrink-0 text-xs leading-none">
                {{ $n['icon'] }}
              </span>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $n['judul'] }}<span class="text-gray-400 text-xs"> {{ $n['waktu'] }}</span></p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $n['pesan'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs">
          Tidak ada notifikasi
        </div>
      @endif

      <button class="mt-1 w-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 py-0.5 sm:py-1 rounded-sm transition text-xs font-medium flex-shrink-0">
        Lihat semua
      </button>
    </div>

    <!-- Absen Donut (1 col, 1 row) -->
    <div class="col-span-1 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl p-1.5 sm:p-2 md:p-3 lg:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col">
      <h3 class="text-xs sm:text-xs md:text-sm lg:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100 flex-shrink-0">Absensi Hari Ini</h3>
      <div class="w-full flex-1 min-h-20 sm:min-h-24 md:min-h-32 lg:min-h-56">
        <canvas id="absenDonut"></canvas>
      </div>
      <div class="mt-1 flex justify-center gap-1 sm:gap-2 text-xs text-gray-600 dark:text-gray-400 flex-shrink-0">
        <span class="truncate">⬛ Sudah</span>
        <span class="truncate">⬜ Belum</span>
      </div>
    </div>

    <!-- Cuti Chart (2 cols, 1 row) -->
    <div class="col-span-2 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl p-1.5 sm:p-2 md:p-3 lg:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col">
      <h3 class="text-xs sm:text-xs md:text-sm lg:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100 flex-shrink-0">Cuti</h3>
      <div class="w-full flex-1 min-h-20 sm:min-h-24 md:min-h-32 lg:min-h-56">
        <canvas id="cutiChart"></canvas>
      </div>
    </div>

    <!-- Live Clock (1 col, 1 row) -->
    <div class="col-span-1 bg-white dark:bg-gray-800 rounded-md sm:rounded-lg md:rounded-lg lg:rounded-xl p-1.5 sm:p-2 md:p-3 lg:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col items-center justify-center" style="min-height: 100px;">
      <div class="text-base sm:text-lg md:text-2xl lg:text-5xl font-bold text-gray-800 dark:text-gray-100" id="liveClock">--:--</div>
      <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5" id="liveDate">--</p>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = ['Sen','Sel','Rab','Kam','Jum'];
  const absensiData = @json($absensiHarian->values());
  const cutiData = @json($cuti->values());

  // Chart Options (Responsive)
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
          font: {
            size: Math.max(8, window.innerWidth < 640 ? 8 : 10)
          }
        },
        grid: {
          color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
        }
      },
      x: {
        ticks: {
          color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
          font: {
            size: Math.max(8, window.innerWidth < 640 ? 8 : 10)
          }
        },
        grid: {
          display: false
        }
      }
    }
  };

  // === Absensi Bar Chart ===
  const absensiCtx = document.getElementById('absensiChart');
  if (absensiCtx) {
    new Chart(absensiCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          data: absensiData,
          backgroundColor: '#fde68a',
          borderRadius: 8,
          borderSkipped: false
        }]
      },
      options: chartOptions
    });
  }

  // === Cuti Bar Chart ===
  const cutiCtx = document.getElementById('cutiChart');
  if (cutiCtx) {
    new Chart(cutiCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Cuti',
          data: cutiData,
          backgroundColor: '#6b7280',
          borderRadius: 8,
          borderSkipped: false
        }]
      },
      options: {
        ...chartOptions,
        plugins: {
          legend: {
            display: true,
            labels: {
              color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
            }
          }
        }
      }
    });
  }

  // === Absen Donut Chart ===
  const donutCtx = document.getElementById('absenDonut');
  if (donutCtx) {
    new Chart(donutCtx, {
      type: 'doughnut',
      data: {
        labels: ['Sudah Absen', 'Belum Absen'],
        datasets: [{
          data: [{{ $sudahAbsen }}, {{ $belumAbsen }}],
          backgroundColor: ['#374151', '#d1d5db'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  }

  // === Live Clock (update tiap detik) ===
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
  }

  updateTime();
  setInterval(updateTime, 1000);
});
</script>
@endsection
