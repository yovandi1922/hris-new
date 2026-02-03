@extends('layouts.admin')

@section('title', 'Slip Gaji')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
<div class="min-h-screen bg-gray-900 text-gray-100 py-8 px-4"
     x-data="slipGajiPage({
        karyawanList: @json($karyawanList),
        slipGajiPeriode: @json($slipGajiPeriode),
        bulan: {{ $bulan }},
        tahun: {{ $tahun }}
     })"
     x-init="console.log('Alpine initialized', $data)">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-2xl font-bold">Gaji Karyawan</h1>
            <form method="GET" class="flex gap-2 items-center">
                <select name="bulan" x-model="periodeBulan" class="bg-gray-800 border border-gray-700 rounded px-3 py-2 focus:outline-none">
                    @foreach([1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $num => $bln)
                        <option value="{{ $num }}" @selected($bulan==$num)> {{ $bln }} </option>
                    @endforeach
                </select>
                <select name="tahun" x-model="periodeTahun" class="bg-gray-800 border border-gray-700 rounded px-3 py-2 focus:outline-none">
                    @for($y = date('Y')-2; $y <= date('Y')+2; $y++)
                        <option value="{{ $y }}" @selected($tahun==$y)> {{ $y }} </option>
                    @endfor
                </select>
                <button type="submit" class="bg-gray-700 px-3 py-2 rounded ml-2">Tampilkan</button>
            </form>
        </div>

        @if(count($karyawanList) === 0)
            <div class="bg-gray-800 rounded p-8 flex flex-col items-center justify-center gap-4">
                <span class="text-lg text-yellow-400">Belum ada data karyawan.</span>
            </div>
        @else
            <div class="mb-6 flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <div class="flex items-center gap-2 text-base">
                    <span>Periode</span>
                    <span class="font-semibold">:</span>
                    <span class="font-semibold">{{ \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y') }}</span>
                </div>
            </div>
            
            <div class="bg-gray-800 rounded p-4 mt-4">
                <div class="mb-2 text-base font-semibold">Gaji Karyawan :
                    <span>{{ \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('j F Y') }} -
                    {{ \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('j F Y') }}</span>
                </div>
                <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-700">
                            <thead>
                                <tr class="bg-gray-700 text-gray-200">
                                    <th class="p-3 font-bold">NIP</th>
                                    <th class="p-3 font-bold">Nama Karyawan</th>
                                    <th class="p-3 font-bold">Jabatan</th>
                                    <th class="p-3 font-bold text-right">Total Gaji</th>
                                    <th class="p-3 font-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawanList as $karyawan)
                                    @php
                                        $slip = $slipGajiPeriode->firstWhere('karyawan_id', $karyawan->id);
                                    @endphp
                                    <tr class="@if($loop->even) bg-gray-900 @endif border-b border-gray-700 hover:bg-gray-700 cursor-pointer transition-colors" 
                                        onclick="window.openDetailModal({{ $karyawan->id }})">
                                        <td class="p-3">{{ $karyawan->nip }}</td>
                                        <td class="p-3 font-semibold text-blue-400">{{ $karyawan->nama }}</td>
                                        <td class="p-3">{{ $karyawan->jabatan }}</td>
                                        <td class="p-3 text-right">{{ $slip ? 'Rp ' . number_format($slip->total_gaji,0,',','.') : '-' }}</td>
                                        <td class="p-3 text-center">
                                            @if($slip && $slip->status == 'dibayar')
                                                <span class="px-2 py-1 rounded bg-green-700 text-green-200">
                                                    Sudah Dibayar
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-gray-600 text-gray-200">Belum Diproses</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        @endif

        <!-- Modal Detail Slip Gaji -->
        <div id="detailModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
            <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 relative text-white">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-200 text-2xl" onclick="closeDetailModal()">&times;</button>
                
                <h2 class="text-xl font-bold mb-4">Detail Slip Gaji</h2>
                
                <!-- Loading state -->
                <div id="modalLoading" class="text-center py-8">
                    <svg class="animate-spin h-12 w-12 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-gray-400">Memuat detail...</p>
                </div>

                <!-- Content -->
                <div id="modalContent" style="display: none;">
                    <!-- Content will be filled by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function slipGajiPage(init) {
    return {
        bulanList: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
        tahunList: Array.from({length: 5}, (_,i) => new Date().getFullYear() - 2 + i),
        periodeBulan: init.bulan,
        periodeTahun: init.tahun,
        karyawanList: init.karyawanList,
        gajiSudahDiproses: (init.slipGajiPeriode||[]).length > 0,
        slipGajiPeriode: init.slipGajiPeriode,
        selectedIds: [],
        selectAll: false,
        showModal: false,
        detailKaryawan: null,
        get periodeLabel() {
            return this.bulanList[this.periodeBulan-1] + ' ' + this.periodeTahun;
        },
        formatRupiah(val) {
            return 'Rp ' + (val || 0).toLocaleString('id-ID');
        },
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = this.karyawanList.map(k => k.id);
            } else {
                this.selectedIds = [];
            }
        },
        openDetail(karyawan) {
            console.log('openDetail called with:', karyawan);
            // Ambil detail dari endpoint
            this.detailKaryawan = null;
            this.showModal = true;
            
            fetch(`{{ url('admin/slip-gaji/detail') }}/${karyawan.id}?bulan=${this.periodeBulan}&tahun=${this.periodeTahun}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('Detail fetched:', data);
                if (data.success) {
                    this.detailKaryawan = data;
                } else {
                    alert(data.message || 'Gagal mengambil detail');
                    this.closeModal();
                }
            })
            .catch(err => {
                console.error('Error fetching detail:', err);
                alert('Error: ' + err.message);
                this.closeModal();
            });
        },
        closeModal() {
            this.showModal = false;
            this.detailKaryawan = null;
        },
        async prosesGajiKaryawan(karyawanId) {
            if (!confirm('Anda yakin ingin memproses gaji karyawan ini?')) {
                return;
            }

            // Proses gaji satu karyawan via AJAX
            let url = `{{ url('admin/slip-gaji/proses') }}/${karyawanId}`;
            let res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bulan: this.periodeBulan, tahun: this.periodeTahun })
            });
            
            let data = await res.json();
            if (data.success) {
                alert(data.message || 'Gaji berhasil diproses');
                this.closeModal();
                window.location.reload(); // Refresh untuk update data
            } else {
                alert(data.message || 'Gagal memproses gaji');
            }
        },
        async prosesGajiTerpilih() {
            if (this.selectedIds.length === 0) {
                alert('Pilih karyawan terlebih dahulu');
                return;
            }
            if (!confirm(`Anda yakin ingin memproses gaji ${this.selectedIds.length} karyawan?`)) {
                return;
            }
            // Proses gaji semua terpilih via AJAX
            for (let id of this.selectedIds) {
                await this.prosesGajiKaryawan(id);
            }
            this.selectedIds = [];
            this.selectAll = false;
        },
        async prosesGajiSemua() {
            if (!confirm('Anda yakin ingin memproses gaji SEMUA karyawan?')) {
                return;
            }
            // Proses gaji semua via AJAX
            let res = await fetch(`{{ route('admin.slipgaji.prosesSemua') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bulan: this.periodeBulan, tahun: this.periodeTahun })
            });
            let data = await res.json();
            if (data.success) {
                alert(data.message || 'Berhasil memproses semua gaji');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal memproses gaji');
            }
        },
    }
}

// Global function untuk open modal dari onclick
window.openDetailModal = function(karyawanId) {
    console.log('Opening detail for karyawan ID:', karyawanId);
    
    const modal = document.getElementById('detailModal');
    const loading = document.getElementById('modalLoading');
    const content = document.getElementById('modalContent');
    
    // Show modal with loading
    modal.style.display = 'flex';
    loading.style.display = 'block';
    content.style.display = 'none';
    
    // Get current periode from Alpine data
    const appEl = document.querySelector('[x-data]');
    const bulan = appEl.__x ? appEl.__x.$data.periodeBulan : {{ $bulan }};
    const tahun = appEl.__x ? appEl.__x.$data.periodeTahun : {{ $tahun }};
    
    // Fetch detail
    fetch(`{{ url('admin/slip-gaji/detail') }}/${karyawanId}?bulan=${bulan}&tahun=${tahun}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log('Detail fetched:', data);
        if (data.success) {
            loading.style.display = 'none';
            content.style.display = 'block';
            renderDetailContent(data, bulan, tahun);
        } else {
            alert(data.message || 'Gagal mengambil detail');
            modal.style.display = 'none';
        }
    })
    .catch(err => {
        console.error('Error fetching detail:', err);
        alert('Error: ' + err.message);
        modal.style.display = 'none';
    });
};

window.closeDetailModal = function() {
    document.getElementById('detailModal').style.display = 'none';
};

window.renderDetailContent = function(data, bulan, tahun) {
    const bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const formatRupiah = (val) => 'Rp ' + (val || 0).toLocaleString('id-ID');
    
    const content = document.getElementById('modalContent');
    const detail = data.detail;
    const karyawan = data.karyawan;
    const sudahDiproses = data.sudah_diproses;
    
    let html = `
        <div class="mb-2">
            <span class="font-semibold">Nama:</span> ${karyawan.nama}
        </div>
        <div class="mb-2">
            <span class="font-semibold">NIP:</span> ${karyawan.nip}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Jabatan:</span> ${karyawan.jabatan}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Periode:</span> ${bulanNames[bulan-1]} ${tahun}
        </div>
        <div class="border-t border-gray-700 my-3"></div>
        
        <!-- Penghasilan -->
        <div class="mb-3">
            <h4 class="font-bold text-green-400 mb-2">Penghasilan</h4>
            <div class="mb-2 flex justify-between text-sm">
                <span>Gaji Pokok</span>
                <span>${formatRupiah(detail.gaji_pokok)}</span>
            </div>
            <div class="mb-2 flex justify-between text-sm">
                <span>Lembur (${detail.total_lembur_jam} jam)</span>
                <span>${formatRupiah(detail.total_lembur)}</span>
            </div>
        </div>

        <!-- Potongan -->
        <div class="mb-3">
            <h4 class="font-bold text-red-400 mb-2">Potongan</h4>
            <div class="mb-2 flex justify-between text-sm">
                <span>Telat (${detail.total_telat_jam} jam)</span>
                <span class="text-red-400">-${formatRupiah(detail.total_telat)}</span>
            </div>
            <div class="mb-2 flex justify-between text-sm">
                <span>Izin (${detail.jumlah_izin} hari)</span>
                <span class="text-red-400">-${formatRupiah(detail.total_izin)}</span>
            </div>
            <div class="mb-2 flex justify-between text-sm">
                <span>Tidak Masuk (${detail.jumlah_tidak_masuk} hari)</span>
                <span class="text-red-400">-${formatRupiah(detail.total_tidak_masuk)}</span>
            </div>
            <div class="mb-2 flex justify-between text-sm">
                <span>Cicilan Bon</span>
                <span class="text-red-400">-${formatRupiah(detail.total_bon)}</span>
            </div>
        </div>
        
        <div class="border-t border-gray-700 my-3"></div>
        <div class="mb-4 flex justify-between font-bold text-lg">
            <span>Total Gaji Bersih</span>
            <span class="text-green-400">${formatRupiah(detail.total_gaji)}</span>
        </div>

        ${sudahDiproses ? `
            <div class="mb-3 p-3 bg-yellow-900 border border-yellow-600 rounded text-sm">
                ⚠️ Slip gaji periode ini sudah diproses sebelumnya
            </div>
        ` : ''}

        <div class="flex gap-2 justify-end">
            <button 
                onclick="prosesGaji(${karyawan.id}, ${bulan}, ${tahun})" 
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold ${sudahDiproses ? 'opacity-50 cursor-not-allowed' : ''}"
                ${sudahDiproses ? 'disabled' : ''}
            >
                ${sudahDiproses ? 'Sudah Diproses' : 'Proses Gaji'}
            </button>
            <button onclick="closeDetailModal()" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded font-semibold">Tutup</button>
        </div>
    `;
    
    content.innerHTML = html;
};

window.prosesGaji = function(karyawanId, bulan, tahun) {
    if (!confirm('Anda yakin ingin memproses gaji karyawan ini?')) {
        return;
    }

    fetch(`{{ url('admin/slip-gaji/proses') }}/${karyawanId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bulan: bulan, tahun: tahun })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Gaji berhasil diproses');
            window.location.reload();
        } else {
            alert(data.message || 'Gagal memproses gaji');
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
    });
};

</script>
@endsection
