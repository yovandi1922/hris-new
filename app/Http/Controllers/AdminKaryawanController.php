<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;

class AdminKaryawanController extends Controller
{
    // Menampilkan daftar karyawan dengan pencarian & pagination
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Karyawan::when($search, function ($query, $search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10); // pagination 10 data per halaman

        return view('admin.karyawan.karyawan', compact('employees'));
    }

    // Form tambah karyawan
    public function create()
    {
        return view('admin.karyawan.karyawan-create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email',
            'departemen' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:100',
            'tanggal_gabung' => 'nullable|date',
        ]);

        Karyawan::create($request->all());
        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    // Form edit karyawan
    public function edit($id)
    {
        $employee = Karyawan::findOrFail($id);
        return view('admin.karyawan.karyawan-edit', compact('employee'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $employee = Karyawan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email,' . $id,
        ]);

        $employee->update($request->all());
        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($id)
    {
        Karyawan::findOrFail($id)->delete();
        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}
