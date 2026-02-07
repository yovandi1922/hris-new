<?php

namespace App\Http\Controllers;

use App\Models\BonGaji;
use App\Models\User;
use Illuminate\Http\Request;

class AdminBonGajiController extends Controller
{
    // Batalkan status pengajuan bon gaji (kembali ke pending)
    public function batal($id)
    {
        $bon = BonGaji::findOrFail($id);
        $bon->status = 'pending';
        $bon->save();
        return redirect()->back()->with('success', 'Status pengajuan bon gaji dibatalkan.');
    }
    // Tampilkan daftar semua pengajuan bon gaji
    public function index()
    {
        $query = BonGaji::with('user')->orderBy('created_at', 'desc');

        // Search
        if (request('q')) {
            $q = request('q');
            $query->whereHas('user', function($u) use ($q) {
                $u->where('name', 'like', "%$q%")
                  ->orWhere('nip', 'like', "%$q%") ;
            })
            ->orWhere('keterangan', 'like', "%$q%") ;
        }

        // Filter status (bisa array)
        if (request('status')) {
            $statuses = (array) request('status');
            $query->whereIn('status', $statuses);
        }

        // Filter tanggal (satu tanggal saja)
        if (request('tanggal')) {
            $query->whereDate('created_at', request('tanggal'));
        }

        $list = $query->get();
        return view('admin.bon.bongaji', compact('list'));
    }

    // Setujui pengajuan bon gaji
    public function approve($id)
    {
        $bon = BonGaji::findOrFail($id);
        $bon->status = 'disetujui';
        $bon->save();
        return redirect()->back()->with('success', 'Pengajuan bon gaji disetujui.');
    }

    // Tolak pengajuan bon gaji
    public function reject($id)
    {
        $bon = BonGaji::findOrFail($id);
        $bon->status = 'ditolak';
        $bon->save();
        return redirect()->back()->with('success', 'Pengajuan bon gaji ditolak.');
    }
}
