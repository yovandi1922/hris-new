<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lembur;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'required',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $lembur = new Lembur();
        $lembur->user_id = Auth::id();
        $lembur->tanggal = $request->tanggal;
        $lembur->jam_mulai = $request->jam_mulai;
        $lembur->jam_selesai = $request->jam_selesai;
        $lembur->keterangan = $request->keterangan;
        if ($request->hasFile('bukti')) {
            $lembur->bukti = $request->file('bukti')->store('bukti_lembur', 'public');
        }
        $lembur->status = 'Menunggu';
        $lembur->save();

        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dikirim!');
    }

    public function indexAdmin(Request $request)
    {
        $query = \App\Models\Lembur::with('user');
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('nip', 'like', "%$search%");
            });
        }
        if ($request->has('status')) {
            $query->whereIn('status', $request->status);
        }
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        $lemburList = $query->orderBy('created_at', 'desc')->get();
        $karyawans = $lemburList->map(function($item) {
            return (object) [
                'id' => $item->id,
                'nip' => $item->user->nip ?? '-',
                'name' => $item->user->name ?? '-',
                'tanggal' => $item->tanggal,
                'jam' => $item->jam_mulai.' - '.$item->jam_selesai,
                'durasi' => \App\Helpers\LemburHelper::hitungJam($item->jam_mulai, $item->jam_selesai),
                'keterangan' => $item->keterangan,
                'status' => $item->status,
            ];
        });
        return view('admin.lembur.daftar', compact('karyawans'));
    }
    
    public function indexKaryawan()
    {
        $riwayatLembur = \App\Models\Lembur::where('user_id', \Auth::id())->orderBy('created_at', 'desc')->get();
        $totalPengajuan = $riwayatLembur->count();
        $totalDisetujui = $riwayatLembur->where('status', 'Disetujui')->count();
        $totalDitolak = $riwayatLembur->where('status', 'Ditolak')->count();
        $totalJam = $riwayatLembur->reduce(function($carry, $item) {
            $carry += \App\Helpers\LemburHelper::getJam($item->jam_mulai, $item->jam_selesai);
            return $carry;
        }, 0);
        return view('karyawan.lembur', compact('riwayatLembur', 'totalPengajuan', 'totalDisetujui', 'totalDitolak', 'totalJam'));
    }

    public function setujui($id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $lembur = \App\Models\Lembur::findOrFail($id);
        if ($lembur->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status lembur sudah diproses.');
        }
        $lembur->status = 'Disetujui';
        $lembur->save();
        return redirect()->back()->with('success', 'Pengajuan lembur disetujui.');
    }

    public function tolak($id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $lembur = \App\Models\Lembur::findOrFail($id);
        if ($lembur->status !== 'Menunggu') {
            return redirect()->back()->with('error', 'Status lembur sudah diproses.');
        }
        $lembur->status = 'Ditolak';
        $lembur->save();
        return redirect()->back()->with('success', 'Pengajuan lembur ditolak.');
    }
}
