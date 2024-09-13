<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\DetailPengajuanModel;
use App\Models\PengajuanModel;
use App\Models\KaryawanModel;
use App\Models\PlanOvertimeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeController extends Controller
{
    public function index() {
        // Ambil data pengajuan dan detailnya
        $pengajuanData = PengajuanModel::with(['detailPengajuan', 'detailPengajuan.planOvertime', 'detailPengajuan.karyawan', 'department'])
            ->orderBy('tanggal', 'DESC')
            ->get();

        // Mengirim data ke view
        return view('overtime.index', compact('pengajuanData'));
    }

    public function planning() {
        $department = DepartmentModel::all(); // Ambil semua departemen
        $karyawanNPK = KaryawanModel::all();
        $jadwalKerja = ['shift 1', 'shift 2', 'shift 3'];
        return view('overtime.planning', compact('department', 'jadwalKerja', 'karyawanNPK'));
    }
    public function store(Request $request)
    {
        // // Debugging - Cek data yang dikirim dari form
        // dd($request->all());

        DB::beginTransaction();
        try {
            // Simpan pengajuan
            $pengajuan = new PengajuanModel();
            $pengajuan->tanggal = $request->tanggal;
            $pengajuan->department_id = $request->department_id;
            $pengajuan->current_status = $request->current_status;
            $pengajuan->current_approver = $request->current_approver;
            $pengajuan->pengajuan_status = $request->pengajuan_status;
            $pengajuan->save();

            // Simpan detail pengajuan dan plan overtime
            foreach ($request->npk as $key => $npk) {
                $detailPengajuan = new DetailPengajuanModel();
                $detailPengajuan->pengajuan_id = $pengajuan->id;
                $detailPengajuan->npk = $npk;
                $detailPengajuan->jadwal_kerja = $request->jadwal_kerja[$key];
                $detailPengajuan->pekerjaan_yang_dilakukan = $request->pekerjaan_yang_dilakukan[$key];
                $detailPengajuan->keterangan = $request->keterangan[$key];
                $detailPengajuan->tul = $request->tul[$key];
                $detailPengajuan->dpstatus = $request->dpstatus;
                $detailPengajuan->save();
                
                $planOvertime = new PlanOvertimeModel();
                $planOvertime->detail_pengajuan_id = $detailPengajuan->id;
                $planOvertime->waktu_mulai = $request->waktu_mulai[$key];
                $planOvertime->waktu_selesai = $request->waktu_selesai[$key];
                $planOvertime->total_waktu = $request->total_waktu[$key];
                $planOvertime->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan lembur berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error menyimpan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
