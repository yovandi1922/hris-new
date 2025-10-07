@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

  <!-- Total Karyawan -->
  <div class="bg-gradient-to-r from-gray-700 to-gray-500 text-white p-6 rounded-lg shadow-md col-span-1">
    <h3 class="text-lg">Total Karyawan</h3>
    <p class="text-4xl font-bold mt-2">1.482</p>
    <div class="mt-4 flex gap-2">
      <button class="px-3 py-1 bg-gray-600 rounded">Edit</button>
      <button class="px-3 py-1 bg-blue-600 rounded">+ Tambah</button>
    </div>
  </div>

  <!-- Absensi -->
  <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
    <h3 class="text-lg font-semibold">Absensi</h3>
    <canvas id="absensiChart"></canvas>
  </div>

  <!-- Notifikasi -->
  <div class="bg-white p-6 rounded-lg shadow-md col-span-1 row-span-2">
    <h3 class="text-lg font-semibold mb-4">Notifikasi</h3>
    <ul class="space-y-3 text-sm max-h-[400px] overflow-y-auto">
      <li class="flex items-start gap-2">
        <span class="text-red-500">❌</span> <div><strong>Absen</strong> - 5 Karyawan belum clock-in</div>
      </li>
      <li class="flex items-start gap-2">
        <span class="text-yellow-500">⚠</span> <div><strong>Penggajian</strong> September belum diproses</div>
      </li>
      <li class="flex items-start gap-2">
        <span class="text-green-500">✅</span> <div><strong>Karyawan</strong> baru ditambahkan</div>
      </li>
    </ul>
    <button class="mt-4 w-full bg-gray-800 text-white py-2 rounded">Lihat Semua</button>
  </div>

  <!-- Jam + Donut -->
  <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
    <div class="text-3xl font-bold">9:14</div>
    <p class="text-gray-500">Wed, September 10</p>
    <canvas id="absenDonut" class="mt-4"></canvas>
  </div>

  <!-- Grafik Cuti -->
  <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
    <h3 class="text-lg font-semibold">Cuti</h3>
    <canvas id="cutiChart"></canvas>
  </div>

</div>
@endsection

@section('scripts')
<script>
  // Absensi Chart
  new Chart(document.getElementById('absensiChart'), {
    type: 'bar',
    data: {
      labels: ['Senin','Selasa','Rabu','Kamis','Jumat'],
      datasets: [{
        data: [1400,1380,1390,1400,1200],
        backgroundColor: '#facc15'
      }]
    }
  });

  // Cuti Chart
  new Chart(document.getElementById('cutiChart'), {
    type: 'bar',
    data: {
      labels: ['Senin','Selasa','Rabu','Kamis','Jumat'],
      datasets: [
        { label: 'Cuti', data: [50,60,80,55,30], backgroundColor: '#374151' },
        { label: 'Pengajuan', data: [20,25,30,20,15], backgroundColor: '#9ca3af' }
      ]
    }
  });

  // Donut Chart
  new Chart(document.getElementById('absenDonut'), {
    type: 'doughnut',
    data: {
      labels: ['Sudah Absen','Belum Absen'],
      datasets: [{
        data: [1200,282],
        backgroundColor: ['#10b981','#d1d5db']
      }]
    }
  });
</script>
@endsection
