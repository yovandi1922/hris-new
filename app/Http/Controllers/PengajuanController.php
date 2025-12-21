<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;

class PengajuanController extends Controller
{
    public function indexKaryawan()
{
    // Ambil semua pengajuan milik user yang login
    $pengajuan = Pengajuan::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->get();

    return view('karyawan.pengajuan', compact('pengajuan'));
}

    // ADMIN – tampilkan semua karyawan
    public function listKaryawan()
    {
        $karyawan = User::where('role', 'karyawan')->get();
        return view('admin.cuti.karyawan', compact('karyawan'));
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
    ]);

    // Upload file jika ada
    $path = null;
    if($request->hasFile('bukti')){
        $file = $request->file('bukti');
        $path = $file->store('bukti_pengajuan', 'public');
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
        'bukti' => $path
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
