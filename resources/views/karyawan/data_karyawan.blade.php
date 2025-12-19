@extends('layouts.karyawan')

@section('title', 'Data Karyawan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    {{-- Breadcrumb --}}
    <div class="max-w-6xl mx-auto px-6 mb-6">
        <nav class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('karyawan.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Dashboard</a>
            <span class="mx-3">/</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium">Data Karyawan</span>
        </nav>
    </div>

    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Data Karyawan</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi profil dan jadwal kerja karyawan</p>
        </div>

        {{-- Informasi Karyawan Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 dark:from-blue-700 dark:to-blue-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Informasi Pribadi</h2>
            </div>

            {{-- Card Body --}}
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- ID Karyawan --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">ID Karyawan</span>
                        <p class="text-xl font-bold text-gray-900 dark:text-white mt-2">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    {{-- Nama --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Lengkap</span>
                        <p class="text-xl font-bold text-gray-900 dark:text-white mt-2">{{ $user->name }}</p>
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 break-all">{{ $user->email }}</p>
                    </div>

                    {{-- Jabatan --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jabatan</span>
                        <div class="mt-2">
                            <span class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $user->jabatan ?? 'Belum Diatur' }}
                            </span>
                        </div>
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Bergabung</span>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                    </div>

                    {{-- Status --}}
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</span>
                        <div class="mt-2">
                            @if($user->status == 'Aktif')
                                <span class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-1 rounded-full text-sm font-medium">
                                    <span class="w-2 h-2 bg-green-600 dark:bg-green-400 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-3 py-1 rounded-full text-sm font-medium">
                                    <span class="w-2 h-2 bg-red-600 dark:bg-red-400 rounded-full"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Kerja Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 dark:from-indigo-700 dark:to-indigo-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Jadwal Kerja Bulanan</h2>
            </div>

            {{-- Card Body --}}
            <div class="p-8">
                {{-- Filter Controls --}}
                <form method="GET" action="{{ route('karyawan.data') }}" class="mb-8">
                    @php
                        use Carbon\Carbon;
                        $currentMonth = request('month', Carbon::now()->month);
                        $currentYear = request('year', Carbon::now()->year);
                    @endphp

                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label for="month" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                            <select name="month" id="month" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                        {{ Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex-1 min-w-[200px]">
                            <label for="year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                            <select name="year" id="year" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm">
                            Tampilkan
                        </button>
                    </div>
                </form>

                {{-- Schedule Grid --}}
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
                        'tgl' => $date->format('d'),
                        'bulan' => $date->translatedFormat('M'),
                        'hari' => $date->translatedFormat('D'),
                        'status' => $status,
                        'isMinggu' => $isMinggu,
                        'isLibur' => $isLibur,
                    ];

                    if ($date->isSunday()) $weekIndex++;
                }
            @endphp

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($weeks as $i => $week)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        {{-- Week Header --}}
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-gray-900 dark:text-white">Minggu ke-{{ $i+1 }}</h3>
                                <span class="inline-block bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ count($week) }} hari
                                </span>
                            </div>
                        </div>

                        {{-- Days List --}}
                        <div class="divide-y divide-gray-200 dark:divide-gray-600 p-4">
                            @foreach($week as $day)
                                <div class="py-3 px-3 flex items-start justify-between hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            {{-- Date Circle --}}
                                            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-lg font-bold text-white
                                                @if($day['isMinggu'] || $day['isLibur'])
                                                    bg-gradient-to-br from-red-500 to-red-600
                                                @else
                                                    bg-gradient-to-br from-green-500 to-green-600
                                                @endif">
                                                <span class="text-sm">{{ $day['tgl'] }}</span>
                                                <span class="text-xs">{{ $day['bulan'] }}</span>
                                            </div>

                                            {{-- Day Info --}}
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $day['hari'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    @if($day['isMinggu'] || $day['isLibur'])
                                                        <span class="text-red-600 dark:text-red-400 font-medium">{{ $day['status'] }}</span>
                                                    @else
                                                        <span class="text-green-600 dark:text-green-400 font-medium">{{ $day['status'] }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data jadwal untuk periode ini</p>
                    </div>
                @endforelse
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
