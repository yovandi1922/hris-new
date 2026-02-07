<?php
namespace App\Http\Controllers;

use App\Models\Lembur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ...existing code...
use Illuminate\Support\Facades\DB;

class AdminLemburController extends Controller
{
    // Batalkan persetujuan/tolak lembur
    public function batal($id)
    {
        $lembur = Lembur::findOrFail($id);
        if ($lembur->status === 'Menunggu') {
            return back()->with('error', 'Pengajuan belum diproses.');
        }
        $lembur->status = 'Menunggu';
        $lembur->approved_by = null;
        $lembur->approved_at = null;
        $lembur->save();
        return back()->with('success', 'Status lembur berhasil dibatalkan.');
    }
    // Hanya admin
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    // Daftar pengajuan lembur
    public function index(Request $request)
    {
        $query = Lembur::with(['user', 'approvedBy']);
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('status')) {
            $query->whereIn('status', (array)$request->status);
        }
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }
        $lemburs = $query->orderByDesc('created_at')->paginate(20);
        return view('admin.lembur.daftar', compact('lemburs'));
    }

    // Setujui lembur
    public function approve($id)
    {
        $lembur = Lembur::findOrFail($id);
        if ($lembur->status !== 'Menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }
        $lembur->status = 'Disetujui';
        $lembur->approved_by = Auth::id();
        $lembur->approved_at = now();
        $lembur->save();
        return back()->with('success', 'Pengajuan lembur disetujui.');
    }

    // Tolak lembur
    public function reject($id)
    {
        $lembur = Lembur::findOrFail($id);
        if ($lembur->status !== 'Menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }
        $lembur->status = 'Ditolak';
        $lembur->approved_by = Auth::id();
        $lembur->approved_at = now();
        $lembur->save();
        return back()->with('success', 'Pengajuan lembur ditolak.');
    }
}
