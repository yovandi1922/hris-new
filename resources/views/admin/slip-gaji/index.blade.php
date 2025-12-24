@extends('layouts.admin')

@section('title', 'Slip Gaji')

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-100 py-8 px-4"
     x-data="slipGajiPage({
        karyawanList: @json($karyawanList),
        slipGajiPeriode: @json($slipGajiPeriode),
        bulan: {{ $bulan }},
        tahun: {{ $tahun }}
     })">
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
            @if(count($slipGajiPeriode) === 0)
                <div class="bg-gray-800 rounded p-8 flex flex-col items-center justify-center gap-4">
                    <span class="text-lg">Belum ada data gaji untuk periode ini.</span>
                    <button @click="prosesGajiSemua()" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-semibold">Proses Gaji</button>
                </div>
            @else
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
                                    <tr class="@if($loop->even) bg-gray-900 @endif border-b border-gray-700">
                                        <td class="p-3">{{ $karyawan->nip ?? '-' }}</td>
                                        <td class="p-3">{{ $karyawan->name }}</td>
                                        <td class="p-3">{{ $karyawan->jabatan ?? '-' }}</td>
                                        <td class="p-3 text-right">{{ $slip ? number_format($slip->total_gaji,0,',','.') : '-' }}</td>
                                        <td class="p-3 text-center">
                                            @if($slip)
                                                <span class="px-2 py-1 rounded @if($slip->status=='dibayar') bg-green-700 text-green-200 @else bg-yellow-700 text-yellow-200 @endif">
                                                    {{ $slip->status == 'dibayar' ? 'Sudah Dibayar' : 'Belum Diproses' }}
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
        @endif

        <!-- Modal Detail Slip Gaji -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
            <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 relative" @click.away="closeModal()">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-200" @click="closeModal()">&times;</button>
                <template x-if="detailKaryawan">
                    <div>
                        <h2 class="text-xl font-bold mb-2">Detail Slip Gaji</h2>
                        <div class="mb-2">
                            <span class="font-semibold">Nama:</span> <span x-text="detailKaryawan.nama"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold">NIP:</span> <span x-text="detailKaryawan.nip"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold">Jabatan:</span> <span x-text="detailKaryawan.jabatan"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold">Periode:</span> <span x-text="periodeLabel"></span>
                        </div>
                        <div class="border-t border-gray-700 my-3"></div>
                        <div class="mb-2 flex justify-between">
                            <span>Gaji Pokok</span>
                            <span x-text="formatRupiah(3000000)"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Tunjangan Transport</span>
                            <span x-text="formatRupiah(100000)"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Tunjangan Makan</span>
                            <span x-text="formatRupiah(100000)"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Total Jam Lembur</span>
                            <span x-text="detailKaryawan.lembur_jam + ' jam'"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Potongan Telat</span>
                            <span x-text="'-' + formatRupiah(detailKaryawan.telat_jam * 25000)"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Potongan Izin</span>
                            <span x-text="'-' + formatRupiah(detailKaryawan.izin * 50000)"></span>
                        </div>
                        <div class="mb-2 flex justify-between">
                            <span>Potongan Cuti</span>
                            <span>-</span>
                        </div>
                        <div class="border-t border-gray-700 my-3"></div>
                        <div class="mb-4 flex justify-between font-bold text-lg">
                            <span>Total Gaji Bersih</span>
                            <span x-text="formatRupiah(hitungTotalGaji(detailKaryawan))"></span>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button @click="prosesGajiKaryawan(detailKaryawan)" x-show="detailKaryawan.status != 'dibayar'" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold">Proses Gaji</button>
                            <button @click="closeModal()" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded font-semibold">Tutup</button>
                        </div>
                    </div>
                </template>
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
        karyawanList: init.karyawanList.map(k => {
            // Gabungkan slip gaji jika ada
            let slip = (init.slipGajiPeriode||[]).find(s => s.user_id === k.id);
            return {
                ...k,
                total_gaji: slip ? slip.total_gaji : 0,
                status: slip ? slip.status : 'pending',
                lembur_jam: slip ? slip.lembur_jam : 0,
                telat_jam: slip ? slip.telat_jam : 0,
                izin: slip ? slip.izin : 0,
            };
        }),
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
            this.detailKaryawan = {...karyawan};
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.detailKaryawan = null;
        },
        hitungTotalGaji(k) {
            let gajiPokok = 3000000;
            let tunjanganTransport = 100000;
            let tunjanganMakan = 100000;
            let lembur = (k.lembur_jam || 0) * 50000;
            let potTelat = (k.telat_jam || 0) * 25000;
            let potIzin = (k.izin || 0) * 50000;
            return gajiPokok + tunjanganTransport + tunjanganMakan + lembur - potTelat - potIzin;
        },
        async prosesGajiKaryawan(karyawan) {
            // Proses gaji satu karyawan via AJAX
            let url = `{{ url('admin/slip-gaji/proses') }}/${karyawan.id}`;
            let res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bulan: this.periodeBulan, tahun: this.periodeTahun })
            });
            if (res.ok) {
                karyawan.status = 'dibayar';
                let idx = this.karyawanList.findIndex(x => x.id === karyawan.id);
                if (idx !== -1) this.karyawanList[idx].status = 'dibayar';
                this.closeModal();
            }
        },
        async prosesGajiTerpilih() {
            // Proses gaji semua terpilih via AJAX
            for (let id of this.selectedIds) {
                let karyawan = this.karyawanList.find(k => k.id === id);
                if (karyawan && karyawan.status !== 'dibayar') {
                    await this.prosesGajiKaryawan(karyawan);
                }
            }
            this.selectedIds = [];
            this.selectAll = false;
        },
        async prosesGajiSemua() {
            // Proses gaji semua via AJAX
            let res = await fetch(`{{ route('admin.slipgaji.prosesSemua') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bulan: this.periodeBulan, tahun: this.periodeTahun })
            });
            if (res.ok) {
                this.gajiSudahDiproses = true;
                // Reload data slip gaji jika perlu
                window.location.reload();
            }
        },
    }
}
</script>
@endsection
