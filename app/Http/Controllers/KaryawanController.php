<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\KaryawanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KaryawanController extends Controller
{
    public function index() {
        // $apikaryawan = Http::('https://3650-182-3-44-213.ngrok-free.app/api/KaryawanGetAll');
        $karyawanData = KaryawanModel::with('department')->orderBy('created_at', 'DESC')->get();
        $roles = ['superadmin', 'karyawan', 'admin', 'section_head', 'department_head', 'division_head', 'hrd'];
        $department = DepartmentModel::all(); // Ambil semua departemen
        return view('karyawan.index', compact('karyawanData', 'roles', 'department'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npk' => 'required|unique:karyawan,npk',
            'password' => 'required',
            'nama' => 'required',
            'no_telp' => 'required',
            'role' => 'required',
            'department_id' => 'required',
        ], 
        [
            'npk.required' => 'NPK wajib diisi.',
            'npk.unique' => 'NPK sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'no_telp.required' => 'no_telp wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'department_id.required' => 'Departemen wajib dipilih.',
        ]);
        $data = [
            'npk' =>$request->npk,
            'password' =>bcrypt($request->password),
            'nama' =>$request->nama,
            'no_telp' =>$request->no_telp,
            'role' =>$request->role,
            'department_id' =>$request->department_id,
        ];
        KaryawanModel::create($data);
        return redirect('karyawan')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function detail($npk) {
        $karyawan = KaryawanModel::where('npk', $npk)->firstOrFail();
        $department = DepartmentModel::find($karyawan->department_id);
    
        return view('karyawan.detail', [
            'karyawan' => $karyawan,
            'department' => $department,
        ]);
    }

    public function edit($npk) {
        $karyawan = KaryawanModel::where('npk', $npk)->firstOrFail();
        $department = DepartmentModel::all();
        $roles = ['superadmin', 'karyawan', 'admin', 'section_head', 'department_head', 'division_head', 'hrd'];
        
        return view('karyawan.update', [
            'karyawan' => $karyawan,
            'department' => $department,
            'roles' => $roles
        ]);
    }
    
    public function update(Request $request, $npk) {
        $request->validate([
            'nama' => 'required',
            'no_telp' => 'required',
            'role' => 'required',
            'department_id' => 'required',
            'password' => 'nullable|min:6',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'no_telp.required' => 'no_telp wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);
    
        $karyawan = KaryawanModel::where('npk', $npk)->firstOrFail();
        $karyawan->nama = $request->nama;
        $karyawan->no_telp = $request->no_telp;
        $karyawan->role = $request->role;
        $karyawan->department_id = $request->department_id;
    
        if ($request->filled('password')) {
            $karyawan->password = bcrypt($request->password);
        }
    
        $karyawan->save();
    
        return redirect('/karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
    }
    
    public function destroy($npk) {
        $karyawan = KaryawanModel::where('npk', $npk)->firstOrFail();
        $karyawan->delete();
    
        return redirect('/karyawan')->with('success', 'Data karyawan berhasil dihapus.');
    }
    
}
