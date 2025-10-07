<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    // Halaman form karyawan
    public function create()
    {
        return view('karyawan.pengajuan');
    }

    // Simpan pengajuan
    public function store(Request $request)
{
    $request->validate([
        'jenis' => 'required',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string',
        'jam_lembur' => 'nullable|integer',
        'nominal' => 'nullable|integer',
        'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $data = $request->all();

    // upload bukti jika ada
    if ($request->hasFile('bukti')) {
        $file = $request->file('bukti');
        $path = $file->store('bukti_pengajuan', 'public');
        $data['bukti'] = $path;
    }

    // isi otomatis
    $data['user_id'] = auth()->id();
    $data['status'] = 'pending';

    \App\Models\Pengajuan::create($data);

    return redirect()->back()->with('success', 'Pengajuan berhasil diajukan!');
}


    // Halaman admin (list pengajuan)
   public function indexKaryawan()
{
    $pengajuan = Pengajuan::where('user_id', auth()->id())->latest()->get();
    return view('karyawan.pengajuan', compact('pengajuan'));
}

public function index()
{
    $pengajuan = Pengajuan::latest()->get(); // semua pengajuan
    return view('admin.pengajuan', compact('pengajuan'));
}


    // Admin update status
   public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,disetujui,ditolak'
    ]);

    $pengajuan = Pengajuan::findOrFail($id);
    $pengajuan->status = $request->status;
    $pengajuan->save();

    return back()->with('success', 'Status pengajuan berhasil diperbarui');
}

}
