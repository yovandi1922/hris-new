<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Absen;

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
        $absensi = Absen::with('user')->latest()->get();
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

    // ✅ Manajemen Gaji (Payroll)
    public function payroll()
    {
        $salaryComponents = [
            'income' => [
                ['id' => 1, 'employee_id' => 101, 'employee_name' => 'Budi Santoso', 'name' => 'Gaji Pokok', 'amount' => 5000000],
                ['id' => 2, 'employee_id' => 102, 'employee_name' => 'Siti Aminah', 'name' => 'Tunjangan Transportasi', 'amount' => 750000],
            ],
            'deductions' => [
                ['id' => 1, 'employee_id' => 101, 'employee_name' => 'Budi Santoso', 'name' => 'BPJS', 'amount' => 250000],
                ['id' => 2, 'employee_id' => 102, 'employee_name' => 'Siti Aminah', 'name' => 'PPh 21', 'amount' => 300000],
            ],
        ];

        return view('admin.payroll', compact('salaryComponents'));
    }

    // ✅ Manajemen Bonus
    public function bonus()
    {
        $bonuses = [
            ['id' => 1, 'employee_id' => 101, 'employee' => 'Budi Santoso', 'amount' => 1000000, 'reason' => 'Kinerja Luar Biasa'],
            ['id' => 2, 'employee_id' => 102, 'employee' => 'Siti Aminah', 'amount' => 750000, 'reason' => 'Target Tercapai'],
        ];

        return view('admin.bonus', compact('bonuses'));
    }

    // ✅ Approval Workflow
    public function approvalWorkflow()
    {
        $loggedUser = Auth::user();

        $approvals = [
            [
                'id' => 1,
                'karyawan' => 'Budi Santoso',
                'jenis' => 'Cuti Sakit',
                'tanggal' => '2025-09-10',
                'status' => 'Pending'
            ],
            [
                'id' => 2,
                'karyawan' => 'Siti Aminah',
                'jenis' => 'Cuti Tahunan',
                'tanggal' => '2025-09-12',
                'status' => 'Disetujui'
            ],
            [
                'id' => 3,
                'karyawan' => 'Andi Pratama',
                'jenis' => 'Cuti Penting',
                'tanggal' => '2025-09-14',
                'status' => 'Ditolak'
            ]
        ];

        return view('admin.approval-workflow', compact('loggedUser', 'approvals'));
    }
}
