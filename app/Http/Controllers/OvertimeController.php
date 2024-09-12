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

        // Cek role dari user yang login
        $role = auth()->user()->role;

        // Atur nilai current_status berdasarkan role
        if ($role == 'admin') {
            $currentStatus = 'pending';
        } 
        
        try {
            // 1. Simpan data ke tabel 'pengajuan'
            $pengajuan = new PengajuanModel();
            $pengajuan->tanggal = $request->tanggal;
            $pengajuan->department_id = $request->department_id;
            $pengajuan->current_status = $currentStatus; // default value
            $pengajuan->current_approver = auth()->user()->npk; // assuming the logged-in user is the approver
            $pengajuan->status = 'pending'; // default value
            $pengajuan->save();

            // 2. Simpan data ke tabel 'detail_pengajuan' untuk setiap karyawan (npk)
            foreach ($request->npk as $key => $npk) {
                $detailPengajuan = new DetailPengajuanModel();
                $detailPengajuan->pengajuan_id = $pengajuan->id;
                $detailPengajuan->npk = $npk;
                $detailPengajuan->jadwal_kerja = $request->jadwal_kerja[$key];
                $detailPengajuan->pekerjaan_yang_dilakukan = $request->pekerjaan_yang_dilakukan[$key];
                $detailPengajuan->keterangan = $request->keterangan[$key];
                $detailPengajuan->tul = $request->tul[$key];
                $detailPengajuan->status = 'pending'; // default value
                $detailPengajuan->save();

                // 3. Simpan data ke tabel 'plan_overtime' (menggunakan detail_pengajuan_id)
                $planOvertime = new PlanOvertimeModel();
                $planOvertime->detail_pengajuan_id = $detailPengajuan->id;
                $planOvertime->waktu_mulai = $request->waktu_mulai;
                $planOvertime->waktu_selesai = $request->waktu_selesai;
                $planOvertime->total_waktu = $request->total_waktu;
                $planOvertime->save();
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Planning Overtime berhasil disimpan.');

        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollback();

            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
