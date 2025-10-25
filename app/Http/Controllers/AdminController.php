<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Absen; // ✅ Tambahkan ini supaya bisa pakai model Absen

class AdminController extends Controller
{
    public function dashboard()
    {
        $loggedUser = Auth::user(); 
        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.dashboard', compact('loggedUser', 'users'));
    }

    public function karyawan(Request $request)
    {
        $query = User::where('role', 'karyawan');

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->orderBy('name', 'asc')->paginate(5);

        return view('admin.karyawan', compact('employees'));
    }

    public function absensi()
    {
        // ✅ Ambil data absensi beserta relasi user
        $absensi = Absen::with('user')->latest()->get();

        // ✅ Kirim variabel $absensi ke view
        return view('admin.absen', compact('absensi'));
    }

    public function createKaryawan()
    {
        $loggedUser = Auth::user();
        return view('admin.karyawan-create', compact('loggedUser'));
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function editKaryawan($id)
    {
        $loggedUser = Auth::user();
        $karyawan = User::findOrFail($id);
        return view('admin.karyawan-edit', compact('karyawan', 'loggedUser'));
    }

    public function updateKaryawan(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$karyawan->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }
        $karyawan->save();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroyKaryawan($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil dihapus');
    }
}
