@extends('layouts.karyawan')

@section('title', 'Data Karyawan')

@section('content')
@php
use Carbon\Carbon;

$month = request('month', now()->month);
$year  = request('year', now()->year);

$firstDay = Carbon::create($year, $month, 1);
$lastDay  = $firstDay->copy()->endOfMonth();
$startCalendar = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
$endCalendar   = $lastDay->copy()->endOfWeek(Carbon::SUNDAY);

/*
|--------------------------------------------------------------------------
| SIMULASI STATUS (NANTI GANTI DB)
|--------------------------------------------------------------------------
*/
function statusHari($date) {
    if ($date->isSunday()) {
        return ['label'=>'Lembur','color'=>'bg-yellow-200 text-yellow-800'];
    }

    if (in_array($date->day,[2,10])) {
        return ['label'=>'Cuti / Izin','color'=>'bg-cyan-200 text-cyan-800'];
    }

    return ['label'=>'Hari Kerja','color'=>'bg-green-200 text-green-800'];
}
@endphp

<div x-data="{ open:false, tanggal:'', status:'' }">

    {{-- Breadcrumb --}}
    <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
        Dashboard / Jadwal Kerja
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                Jadwal Kerja
            </h1>

            {{-- Navigasi Bulan --}}
            <div class="flex items-center gap-4 text-sm">
                <a href="?month={{ $firstDay->copy()->subMonth()->month }}&year={{ $firstDay->copy()->subMonth()->year }}"
                   class="px-3 py-1 rounded border hover:bg-gray-100 dark:hover:bg-gray-700">
                    ‹
                </a>

                <span class="font-semibold">
                    {{ $firstDay->translatedFormat('F Y') }}
                </span>

                <a href="?month={{ $firstDay->copy()->addMonth()->month }}&year={{ $firstDay->copy()->addMonth()->year }}"
                   class="px-3 py-1 rounded border hover:bg-gray-100 dark:hover:bg-gray-700">
                    ›
                </a>
            </div>
        </div>

        {{-- Kalender --}}
        <div class="p-6">
            <div class="grid grid-cols-7 border rounded-xl overflow-hidden">

                {{-- Header Hari --}}
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $i => $day)
                    <div class="py-3 text-center text-sm font-semibold
                        {{ $i >= 5 ? 'text-red-500' : 'text-gray-600' }}
                        bg-gray-100 dark:bg-gray-700">
                        {{ $day }}
                    </div>
                @endforeach

                {{-- Tanggal --}}
                @for($date = $startCalendar->copy(); $date <= $endCalendar; $date->addDay())
                    @php $status = statusHari($date); @endphp

                    <div
                        @click="
                            open = true;
                            tanggal = '{{ $date->translatedFormat('d F Y') }}';
                            status  = '{{ $status['label'] }}';
                        "
                        class="relative h-28 border p-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700
                        {{ $date->month != $month ? 'bg-gray-50 dark:bg-gray-800/40' : '' }}">

                        {{-- Angka --}}
                        <div class="text-sm font-semibold
                            {{ $date->isSunday() ? 'text-red-500' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $date->day }}
                        </div>

                        {{-- Status --}}
                        @if($date->month == $month)
                            <div class="absolute bottom-2 left-2 right-2 text-xs px-2 py-1 rounded {{ $status['color'] }}">
                                {{ $status['label'] }}
                            </div>
                        @endif
                    </div>
                @endfor
            </div>

            {{-- Legend --}}
            <div class="flex gap-6 mt-6 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-green-200 rounded"></span> Hari Kerja
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-yellow-200 rounded"></span> Lembur
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-cyan-200 rounded"></span> Cuti / Izin
                </div>
            </div>
        </div>
    </div>

    {{-- ================= POPUP DETAIL ================= --}}
    <div x-show="open" x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div @click.outside="open=false"
             class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md p-6 shadow-lg">

            <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">
                Detail Jadwal
            </h2>

            <div class="space-y-2 text-sm">
                <p><strong>Tanggal:</strong> <span x-text="tanggal"></span></p>
                <p><strong>Status:</strong> <span x-text="status"></span></p>
            </div>

            <div class="mt-6 text-right">
                <button @click="open=false"
                        class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
