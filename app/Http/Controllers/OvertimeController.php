<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\DetailPengajuanModel;
use App\Models\PengajuanModel;
use App\Models\PlanOvertimeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OvertimeController extends Controller
{
    public function index() {
        return view('overtime.index');
    }

    public function planning() {
        $department = DepartmentModel::all(); // Ambil semua departemen
        $roles = ['superadmin', 'karyawan', 'admin', 'section_head', 'department_head', 'division_head', 'hrd'];
        $jadwalKerja = ['shift 1', 'shift 2', 'shift 3'];
        $apiData = $this->getAPI();
        return view('overtime.planning', compact('department', 'roles', 'apiData', 'jadwalKerja'));
    }
    public function getAPI()
    {
        // Logika untuk mengambil data API
        $response = Http::get('https://tesapi.trafficupindonesia.com/api/karyawanGetAll');
        
        if ($response->successful()) {
            return $response->json(); // Return data dari API
        }
    
        return []; // Return array kosong jika ada masalah
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
    
        $role = auth()->user()->role;
        $currentStatus = $role == 'admin' ? 'pending' : 'some_default_status'; // Adjust as needed
    
        try {
            $pengajuan = new PengajuanModel();
            $pengajuan->tanggal = $request->tanggal;
            $pengajuan->department_id = $request->department_id;
            $pengajuan->current_status = $currentStatus;
            $pengajuan->current_approver = auth()->user()->npk;
            $pengajuan->status = 'pending';
            $pengajuan->save();
    
            foreach ($request->npk as $key => $npk) {
                $detailPengajuan = new DetailPengajuanModel();
                $detailPengajuan->pengajuan_id = $pengajuan->id;
                $detailPengajuan->npk = $npk;
                $detailPengajuan->jadwal_kerja = $request->jadwal_kerja[$key];
                $detailPengajuan->pekerjaan_yang_dilakukan = $request->pekerjaan_yang_dilakukan[$key];
                $detailPengajuan->keterangan = $request->keterangan[$key];
                $detailPengajuan->tul = $request->tul[$key];
                $detailPengajuan->status = 'pending';
                $detailPengajuan->save();
    
                // Save plan overtime for each detail pengajuan
                $planOvertime = new PlanOvertimeModel();
                $planOvertime->detail_pengajuan_id = $detailPengajuan->id;
                $planOvertime->waktu_mulai = $request->waktu_mulai[$key];
                $planOvertime->waktu_selesai = $request->waktu_selesai[$key];
                $planOvertime->total_waktu = $request->total_waktu[$key];
                $planOvertime->save();
            }
    
            DB::commit();
            return redirect()->back()->with('success', 'Planning Overtime berhasil disimpan.');
    
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
