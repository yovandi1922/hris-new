@extends('layouts.karyawan')

@section('title', 'Data Karyawan')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-10 transition-colors duration-300">
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                Data Karyawan
            </h1>
            <div class="h-1 w-24 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full"></div>
        </div>

        {{-- Informasi Karyawan --}}
        <div class="bg-gray-50 dark:bg-gray-900/40 rounded-xl p-6 mb-10 border border-gray-200 dark:border-gray-700 shadow-sm">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Informasi Pribadi</h2>

            <div class="grid sm:grid-cols-2 gap-y-3 gap-x-10 text-gray-700 dark:text-gray-300">
                <p><span class="font-semibold text-gray-600 dark:text-gray-400">ID Karyawan:</span> {{ $user->id }}</p>
                <p><span class="font-semibold text-gray-600 dark:text-gray-400">Nama:</span> {{ $user->name }}</p>
                <p><span class="font-semibold text-gray-600 dark:text-gray-400">Email:</span> {{ $user->email }}</p>
                <p><span class="font-semibold text-gray-600 dark:text-gray-400">Jabatan:</span> {{ $user->jabatan ?? '-' }}</p>
                <p><span class="font-semibold text-gray-600 dark:text-gray-400">Tanggal Bergabung:</span> {{ $user->created_at->format('d M Y') }}</p>
                <p>
                    <span class="font-semibold text-gray-600 dark:text-gray-400">Status:</span>
                    <span class="{{ $user->status == 'Aktif' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $user->status ?? 'Aktif' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Jadwal Kerja --}}
        <div>
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-2">
                Jadwal Kerja Bulanan
            </h2>

            {{-- Filter --}}
            <form method="GET" action="{{ route('karyawan.data') }}" class="flex flex-wrap gap-3 mb-8">
                @php
                    use Carbon\Carbon;
                    $currentMonth = request('month', Carbon::now()->month);
                    $currentYear = request('year', Carbon::now()->year);
                @endphp

                <select name="month" class="border rounded-xl p-2 px-3 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                            {{ Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="border rounded-xl p-2 px-3 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-5 py-2 rounded-xl font-medium hover:opacity-90 transition">
                    Tampilkan
                </button>
            </form>

            {{-- Jadwal Ringkas --}}
            @php
                $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
                $endOfMonth = $startOfMonth->copy()->endOfMonth();

                $hariLiburNasional = [
                    '2025-01-01' => 'Tahun Baru Masehi',
                    '2025-03-29' => 'Nyepi',
                    '2025-04-18' => 'Wafat Isa Almasih',
                    '2025-05-01' => 'Hari Buruh',
                    '2025-05-29' => 'Kenaikan Isa Almasih',
                    '2025-06-06' => 'Idul Adha',
                    '2025-08-17' => 'Hari Kemerdekaan RI',
                    '2025-12-25' => 'Natal',
                ];

                $weeks = [];
                $weekIndex = 0;
                for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                    $tanggal = $date->format('Y-m-d');
                    $isMinggu = $date->isSunday();
                    $isLibur = array_key_exists($tanggal, $hariLiburNasional);
                    $status = $isMinggu ? 'Libur Minggu' : ($isLibur ? $hariLiburNasional[$tanggal] : 'Hari Kerja');

                    $weeks[$weekIndex][] = [
                        'tgl' => $date->format('d M'),
                        'hari' => $date->translatedFormat('D'),
                        'status' => $status,
                        'color' => $isMinggu || $isLibur
                            ? 'text-red-600 bg-red-50 dark:bg-red-900/40'
                            : 'text-green-700 bg-green-50 dark:bg-green-900/30'
                    ];

                    if ($date->isSunday()) $weekIndex++;
                }
            @endphp

            {{-- Tampilan per Minggu --}}
            <div class="grid gap-5 md:grid-cols-2">
                @foreach($weeks as $i => $week)
                    <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">Minggu ke-{{ $i+1 }}</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($week) }} Hari</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($week as $day)
                                <div class="flex justify-between items-center px-4 py-2 rounded-lg {{ $day['color'] }}">
                                    <span class="font-medium">{{ $day['hari'] }}, {{ $day['tgl'] }}</span>
                                    <span class="text-sm">{{ $day['status'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
