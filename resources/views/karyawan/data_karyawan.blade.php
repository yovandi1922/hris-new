@extends('layouts.karyawan')

@section('title', 'Data Karyawan')

@section('content')
<div class="flex h-auto dark:bg-gray-900 dark:text-gray-200 transition-colors duration-300">
    <main class="w-full p-6 bg-gray-100 dark:bg-gray-800">
        <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-6 max-w-3xl mx-auto transition-colors duration-300">
            <h1 class="text-2xl font-bold mb-6 text-gray-700 dark:text-gray-100">Data Karyawan</h1>

            {{-- Informasi Karyawan --}}
            <div class="space-y-4 mb-8">
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">ID Karyawan:</span>
                    <span>{{ $user->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Nama:</span>
                    <span>{{ $user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Email:</span>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Jabatan:</span>
                    <span>{{ $user->jabatan ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Tanggal Bergabung:</span>
                    <span>{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Status:</span>
                    <span class="{{ $user->status == 'Aktif' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $user->status ?? 'Aktif' }}
                    </span>
                </div>
            </div>

            {{-- Jadwal Kerja --}}
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-100 mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">
                Jadwal Kerja Bulanan
            </h2>

            {{-- Filter Bulan dan Tahun --}}
            <form method="GET" action="{{ route('karyawan.data') }}" class="mb-6 flex space-x-2">
                @php
                    use Carbon\Carbon;
                    $currentMonth = request('month', Carbon::now()->month);
                    $currentYear = request('year', Carbon::now()->year);
                @endphp

                <select name="month" class="border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                            {{ Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                    Tampilkan
                </button>
            </form>

            {{-- Kalender Berdasarkan Bulan & Tahun --}}
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
            @endphp

            <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden text-sm">
                <thead class="bg-blue-600 text-white dark:bg-gray-700">
                    <tr>
                        <th class="py-2 px-3 text-left w-1/3">Tanggal</th>
                        <th class="py-2 px-3 text-left w-1/3">Hari</th>
                        <th class="py-2 px-3 text-left w-1/3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay())
                        @php
                            $tanggal = $date->format('Y-m-d');
                            $hari = $date->translatedFormat('l');
                            $isMinggu = $date->isSunday();
                            $isLiburNasional = array_key_exists($tanggal, $hariLiburNasional);
                        @endphp

                        <tr class="{{ $isMinggu || $isLiburNasional ? 'bg-red-50 dark:bg-red-900/30' : 'bg-green-50 dark:bg-green-900/30' }}">
                            <td class="border-t border-gray-200 dark:border-gray-700 px-3 py-2">{{ $date->format('d M Y') }}</td>
                            <td class="border-t border-gray-200 dark:border-gray-700 px-3 py-2">{{ $hari }}</td>
                            <td class="border-t border-gray-200 dark:border-gray-700 px-3 py-2">
                                @if ($isMinggu)
                                    <span class="text-red-600 dark:text-red-400 font-semibold">Libur Mingguan</span>
                                @elseif ($isLiburNasional)
                                    <span class="text-red-600 dark:text-red-400 font-semibold">Libur Nasional: {{ $hariLiburNasional[$tanggal] }}</span>
                                @else
                                    <span class="text-green-700 dark:text-green-400 font-semibold">Hari Kerja</span>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection
