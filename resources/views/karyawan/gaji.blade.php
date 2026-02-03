@extends('layouts.karyawan')

@section('title', 'Slip Gaji')

@section('content')
<div class="min-h-screen py-8 px-4 transition-colors duration-300"
     x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
     :class="darkMode ? 'bg-gray-900 text-gray-100' : 'bg-gradient-to-br from-white via-gray-50 to-gray-100 text-gray-900'">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-2xl font-bold">Slip Gaji Saya</h1>
            
            <form method="GET" class="flex gap-2 items-center">
                <select name="bulan" 
                        class="rounded px-3 py-2 focus:outline-none transition-colors"
                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900'">
                    <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                </select>
                <select name="tahun" 
                        class="rounded px-3 py-2 focus:outline-none transition-colors"
                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-gray-100' : 'bg-white border-gray-300 text-gray-900'">
                    @for($y = date('Y')-2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white transition">Lihat</button>
            </form>
        </div>

        @if(!$karyawan)
            <div class="rounded p-6 text-center transition-colors"
                 :class="darkMode ? 'bg-red-900 border border-red-700' : 'bg-red-100 border border-red-300 text-red-900'">
                <p class="text-lg">Data karyawan tidak ditemukan. Hubungi admin.</p>
            </div>
        @elseif(!$slipGaji)
            <div class="rounded p-8 text-center transition-colors"
                 :class="darkMode ? 'bg-gray-800' : 'bg-white shadow-lg'">
                <svg class="w-16 h-16 mx-auto mb-4 transition-colors" 
                     :class="darkMode ? 'text-gray-600' : 'text-gray-400'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg transition-colors" 
                   :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Slip gaji untuk periode ini belum tersedia.</p>
                <p class="text-sm mt-2 transition-colors" 
                   :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Silakan hubungi admin atau tunggu proses penggajian.</p>
            </div>
        @else
            <div class="rounded-lg shadow-lg overflow-hidden transition-colors"
                 :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-br from-white to-gray-50'">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
                    <h2 class="text-xl font-bold mb-2 text-white">SLIP GAJI</h2>
                    <p class="text-blue-100">Periode: {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</p>
                </div>

                <div class="p-6">
                    <div class="mb-6 pb-6 transition-colors"
                         :class="darkMode ? 'border-b border-gray-700' : 'border-b border-gray-200'">
                        <h3 class="text-lg font-semibold mb-3 text-blue-600">Data Karyawan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">NIP:</span>
                                <span class="ml-2 font-medium">{{ $karyawan->nip }}</span>
                            </div>
                            <div>
                                <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Nama:</span>
                                <span class="ml-2 font-medium">{{ $karyawan->nama }}</span>
                            </div>
                            <div>
                                <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Jabatan:</span>
                                <span class="ml-2 font-medium">{{ $karyawan->jabatan }}</span>
                            </div>
                            <div>
                                <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Status:</span>
                                <span class="ml-2 px-2 py-1 rounded text-sm {{ $slipGaji->status == 'dibayar' ? 'bg-green-700 text-green-200' : 'bg-yellow-700 text-yellow-200' }}">
                                    {{ $slipGaji->status == 'dibayar' ? 'Sudah Dibayar' : 'Proses' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3 text-green-600">Penghasilan</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Gaji Pokok</span>
                                <span class="font-medium">Rp 3.000.000</span>
                            </div>
                            @if($slipGaji->total_lembur_jam > 0)
                            <div class="flex justify-between text-sm">
                                <span>Lembur ({{ $slipGaji->total_lembur_jam }} jam x Rp 50.000)</span>
                                <span class="font-medium">Rp {{ number_format($slipGaji->total_lembur_jam * 50000, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($slipGaji->potongan > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3 text-red-600">Potongan</h3>
                        <div class="space-y-2 text-sm">
                            @if($slipGaji->total_telat_jam > 0)
                            <div class="flex justify-between">
                                <span>Telat ({{ $slipGaji->total_telat_jam }} jam x Rp 25.000)</span>
                                <span class="font-medium text-red-600">- Rp {{ number_format($slipGaji->total_telat_jam * 25000, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between">
                                <span>Total Potongan</span>
                                <span class="font-medium text-red-600">- Rp {{ number_format($slipGaji->potongan, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="pt-6 transition-colors"
                         :class="darkMode ? 'border-t-2 border-gray-700' : 'border-t-2 border-gray-300'">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">Total Gaji Bersih</span>
                            <span class="text-2xl font-bold text-green-600">Rp {{ number_format($slipGaji->total_gaji, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 text-sm transition-colors"
                         :class="darkMode ? 'border-t border-gray-700 text-gray-400' : 'border-t border-gray-200 text-gray-600'">
                        <p>Slip gaji ini dibuat pada: {{ $slipGaji->created_at->format('d F Y, H:i') }}</p>
                        <p class="mt-1">Simpan slip gaji ini sebagai bukti pembayaran.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
