<?php

namespace App\Http\Controllers;

use App\Models\BonGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonGajiController extends Controller
{
    // Tampilkan riwayat bon gaji user
    public function index()
    {
        $user = Auth::user();
        $riwayat = BonGaji::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $summary = [
            'total_pengajuan' => $riwayat->count(),
            'jumlah_bon' => $riwayat->sum('jumlah'),
            'disetujui' => $riwayat->where('status', 'disetujui')->sum('jumlah'),
            'ditolak' => $riwayat->where('status', 'ditolak')->sum('jumlah'),
        ];
        return view('karyawan.bon_gaji', compact('riwayat', 'summary'));
    }

    // Simpan pengajuan bon gaji baru
    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);
        BonGaji::create([
            'user_id' => Auth::id(),
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'status' => 'pending',
        ]);
        return redirect()->back()->with('success', 'Pengajuan bon gaji berhasil diajukan.');
    }
}
