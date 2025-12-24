<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;

class PengajuanController extends Controller
{
    // Download lampiran pengajuan
    public function downloadLampiran($filename)
    {
        $path = storage_path('app/public/bukti_pengajuan/' . $filename);
        if (!file_exists($path)) {
            \Log::error('Lampiran tidak ditemukan: ' . $path);
            return response('File lampiran tidak ditemukan di server.', 404);
        }
        $pengajuan = \App\Models\Pengajuan::where('bukti', 'like', '%'.$filename)->first();
        $originalName = $pengajuan && $pengajuan->bukti_nama_asli ? $pengajuan->bukti_nama_asli : $filename;
        $mime = \Illuminate\Support\Facades\File::mimeType($path);
        // Cek file valid (ukuran minimal 1KB, bukan HTML/error)
        if (filesize($path) < 100) {
            \Log::error('Lampiran corrupt atau kosong: ' . $path);
            return response('File lampiran corrupt atau kosong. Silakan upload ulang.', 500);
        }
        return response()->download($path, $originalName, ['Content-Type' => $mime]);
    }
    // ADMIN – Batalkan pengajuan
    public function batal($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        if ($pengajuan->status == 'pending' && \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->gte(\Carbon\Carbon::today())) {
            $pengajuan->status = 'batal';
            $pengajuan->save();
            return back()->with('success', 'Pengajuan berhasil dibatalkan.');
        } elseif ($pengajuan->status == 'acc' || $pengajuan->status == 'ditolak') {
            $pengajuan->status = 'pending';
            $pengajuan->save();
            return back()->with('success', 'Status pengajuan dikembalikan ke menunggu persetujuan.');
        }
        return back()->with('error', 'Pengajuan tidak dapat dibatalkan.');
    }
    public function indexKaryawan()
{
    // Ambil semua pengajuan milik user yang login
    $userId = auth()->id();
    $pengajuan = Pengajuan::where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();

    // Hitung sisa cuti, diambil, dan ditolak
    $totalCutiTahunan = 12; // misal default 12 hari per tahun
    $cutiDiambil = Pengajuan::where('user_id', $userId)
        ->where('jenis', 'Cuti Tahunan')
        ->where('status', 'acc')
        ->sum('durasi');
    $cutiDitolak = Pengajuan::where('user_id', $userId)
        ->where('jenis', 'Cuti Tahunan')
        ->where('status', 'ditolak')
        ->sum('durasi');
    $sisaCuti = $totalCutiTahunan - $cutiDiambil;

    return view('karyawan.pengajuan', compact('pengajuan', 'sisaCuti', 'cutiDiambil', 'cutiDitolak'));
}

    // ADMIN – tampilkan semua pengajuan cuti/izin seluruh karyawan
    public function listKaryawan()
    {
        $query = Pengajuan::with('user')->orderBy('created_at', 'desc');
        // Filter search
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('nip', 'like', "%$search%");
            });
        }
        // Filter status
        if (request('status')) {
            $status = request('status');
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }
        // Filter jenis cuti
        if (request('jenis')) {
            $jenis = request('jenis');
            if (is_array($jenis)) {
                $query->whereIn('jenis', $jenis);
            } else {
                $query->where('jenis', $jenis);
            }
        }
        // Filter tanggal pengajuan
        if (request('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', request('tanggal_mulai'));
        }
        if (request('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', request('tanggal_selesai'));
        }
        $pengajuan = $query->get();
        return view('admin.cuti.karyawan', compact('pengajuan'));
    }

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'jenis' => 'required|string',
        'keterangan' => 'nullable|string',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ], [
        'bukti.mimes' => 'Lampiran harus berupa file gambar (jpg, jpeg, png) atau PDF.',
        'bukti.max' => 'Ukuran file lampiran maksimal 2MB.'
    ]);

    // Upload file jika ada
    $path = null;
    $originalName = null;
    if($request->hasFile('bukti')){
        $file = $request->file('bukti');
        $originalName = $file->getClientOriginalName();
        // Simpan file dengan nama unik (timestamp + nama asli)
        $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
        try {
            $path = $file->storeAs('bukti_pengajuan', $uniqueName, 'public');
            // Cek file benar-benar tersimpan dan tidak kosong
            $fullPath = storage_path('app/public/bukti_pengajuan/' . $uniqueName);
            if (!file_exists($fullPath) || filesize($fullPath) < 100) {
                \Log::error('Upload gagal atau file corrupt: ' . $fullPath);
                return back()->with('error', 'Upload lampiran gagal atau file corrupt. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            \Log::error('Upload gagal: ' . $e->getMessage());
            return back()->with('error', 'Upload lampiran gagal. Silakan coba lagi.');
        }
    }

    // Hitung durasi
    $start = $request->tanggal_mulai;
    $end = $request->tanggal_selesai;
    $duration = (strtotime($end) - strtotime($start)) / (60*60*24) + 1;

    // Simpan ke database
    Pengajuan::create([
        'user_id' => auth()->id(),
        'jenis' => $request->jenis,
        'keterangan' => $request->keterangan,
        'tanggal' => $start, // <-- wajib diisi agar tidak error
        'tanggal_mulai' => $start,
        'tanggal_selesai' => $end,
        'durasi' => $duration,
        'status' => 'pending',
        'bukti' => $path,
        'bukti_nama_asli' => $originalName,
    ]);

    return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
}



    // ADMIN – tampilkan pengajuan per karyawan
    public function pengajuanByKaryawan($id)
    {
        $karyawan = User::findOrFail($id);

        $pengajuan = Pengajuan::where('user_id', $id)
            ->orderBy('created_at','desc')
            ->get();

        return view('admin.cuti.pengajuan', compact('karyawan','pengajuan'));
    }

    // ADMIN – ACC
    public function acc($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'acc';
        $pengajuan->save();

        return back()->with('success','Pengajuan disetujui.');
    }

    // ADMIN – Tolak
    public function tolak($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'ditolak';
        $pengajuan->save();

        return back()->with('error','Pengajuan ditolak.');
    }
}
