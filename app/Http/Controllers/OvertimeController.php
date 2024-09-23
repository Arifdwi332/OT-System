<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\DetailPengajuanModel;
use App\Models\PengajuanModel;
use App\Models\KaryawanModel;
use App\Models\PlanOvertimeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeController extends Controller
{
    public function index()
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');
        $pengajuanData = PengajuanModel::with(['department', 'detailPengajuan'])
            ->get()
            ->map(function ($pengajuan) {
                return [
                    'id' => $pengajuan->id,
                    'tanggal' => $pengajuan->tanggal,
                    'department' => $pengajuan->department->nama,
                    'hari' => Carbon::parse($pengajuan->tanggal)->translatedFormat('l'), // Menghitung hari dalam bahasa Indonesia
                    'jumlah_member' => $pengajuan->detailPengajuan->count() // Menghitung jumlah member
                ];
            });
        
        return view('overtime.index', compact('pengajuanData'));
    }

    public function show($id)
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');
        // Ambil data pengajuan berdasarkan ID
        $pengajuan = PengajuanModel::with(['department', 'detailPengajuan.planOvertime'])->findOrFail($id);
        
        // Jika data ditemukan, kirim ke view detail
        return view('overtime.detail', compact('pengajuan'));
    }
    
    public function planning() {
        $department = DepartmentModel::all(); // Ambil semua departemen
        $karyawanNPK = KaryawanModel::all();
        $jadwalKerja = ['shift 1', 'shift 2', 'shift 3'];
        return view('overtime.planning', compact('department', 'jadwalKerja', 'karyawanNPK'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'department_id' => 'required|exists:department,id',
            'npk.*' => 'required|exists:karyawan,npk',
            'jadwal_kerja.*' => 'required|string',
            'pekerjaan_yang_dilakukan.*' => 'required|string',
            'waktu_mulai.*' => 'required|date_format:H:i',
            'waktu_selesai.*' => 'required|date_format:H:i',
            'planning_waktu.*' => 'required|string',
            'keterangan.*' => 'nullable|string',
            'tul.*' => 'nullable|string',
            'action.*' => 'required|string',
            'planning_status.*' => 'nullable|string',
            'actual_status.*' => 'nullable|string',
            'reject_reason.*' => 'nullable|string',
            'current_approver.*' => 'required|exists:karyawan,npk',
        ]);
    
        // Start the database transaction
        DB::beginTransaction();
    
        try {
            // Save the Pengajuan
            $pengajuan = new PengajuanModel();
            $pengajuan->tanggal = $request->tanggal;
            $pengajuan->department_id = $request->department_id;
            $pengajuan->save();
    
            $npkArray = $request->npk;
            $jadwalKerjaArray = $request->jadwal_kerja;
            $pekerjaanArray = $request->pekerjaan_yang_dilakukan;
            $waktuMulaiArray = $request->waktu_mulai;
            $waktuSelesaiArray = $request->waktu_selesai;
            $planningWaktuArray = $request->planning_waktu;
            $keteranganArray = $request->keterangan;
            $tulArray = $request->tul;
            $actionArray = $request->action;
            $planningStatusArray = $request->planning_status;
            $actualStatusArray = $request->actual_status;
            $rejectReasonArray = $request->reject_reason;
            $currentApproverArray = $request->current_approver;
    
            // Ensure all arrays are the same length
            $count = count($npkArray);
            for ($key = 0; $key < $count; $key++) {
                $detailPengajuan = new DetailPengajuanModel();
                $detailPengajuan->pengajuan_id = $pengajuan->id;
                $detailPengajuan->npk = $npkArray[$key] ?? null;
                $detailPengajuan->jadwal_kerja = $jadwalKerjaArray[$key] ?? null;
                $detailPengajuan->pekerjaan_yang_dilakukan = $pekerjaanArray[$key] ?? null;
                $detailPengajuan->keterangan = $keteranganArray[$key] ?? null;
                $detailPengajuan->tul = $tulArray[$key] ?? null;
                $detailPengajuan->planning_status = $planningStatusArray[$key] ?? 'draft';
                $detailPengajuan->actual_status = $actualStatusArray[$key] ?? 'draft';
                $detailPengajuan->reject_reason = $rejectReasonArray[$key] ?? 'test';
                $detailPengajuan->current_approver = $currentApproverArray[$key] ?? Auth::user()->npk;
                $detailPengajuan->action = $actionArray[$key];
                $detailPengajuan->save();
    
                $planOvertime = new PlanOvertimeModel();
                $planOvertime->detail_pengajuan_id = $detailPengajuan->id;
                $planOvertime->waktu_mulai = $waktuMulaiArray[$key] ?? null;
                $planOvertime->waktu_selesai = $waktuSelesaiArray[$key] ?? null;
                $planOvertime->planning_waktu = $planningWaktuArray[$key] ?? null;
                $planOvertime->save();
            }
    
            // Commit the transaction
            DB::commit();
            return redirect()->route('overtime.index')->with('success', 'Pengajuan lembur berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback the transaction if something failed
            DB::rollback();
            Log::error('Error menyimpan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }    
    public function edit($id)
    {
        // Get Pengajuan and related DetailPengajuan and PlanOvertime data
        $pengajuan = PengajuanModel::with(['department', 'detailPengajuan.planOvertime'])->findOrFail($id);
        $department = DepartmentModel::all(); // Get all departments
        $karyawanNPK = KaryawanModel::all();
        $planOvertime = PlanOvertimeModel::all();
        $jadwalKerja = ['shift 1', 'shift 2', 'shift 3'];
    
        // Send the data to the edit view
        return view('overtime.update', compact('pengajuan', 'department', 'jadwalKerja', 'karyawanNPK', 'planOvertime'));
    }
    public function update(Request $request, $id)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // Update Pengajuan
            $pengajuan = PengajuanModel::findOrFail($id);
            $pengajuan->tanggal = $request->tanggal;
            $pengajuan->department_id = $request->department_id;
            $pengajuan->save();

            // Delete existing detailPengajuan and planOvertime for re-insertion
            DetailPengajuanModel::where('pengajuan_id', $id)->delete();
            
            // Re-insert the detailPengajuan and planOvertime data
            $npkArray = $request->npk;
            $jadwalKerjaArray = $request->jadwal_kerja;
            $pekerjaanArray = $request->pekerjaan_yang_dilakukan;
            $waktuMulaiArray = $request->waktu_mulai;
            $waktuSelesaiArray = $request->waktu_selesai;
            $planningWaktuArray = $request->planning_waktu;
            $keteranganArray = $request->keterangan;
            $tulArray = $request->tul;
            $actionArray = $request->action;
            $planningStatusArray = $request->planning_status;
            $actualStatusArray = $request->actual_status;
            $rejectReasonArray = $request->reject_reason;
            $currentApproverArray = $request->current_approver;

            // Ensure all arrays are the same length
            $count = count($npkArray);
            for ($key = 0; $key < $count; $key++) {
                $detailPengajuan = new DetailPengajuanModel();
                $detailPengajuan->pengajuan_id = $pengajuan->id;
                $detailPengajuan->npk = $npkArray[$key] ?? null;
                $detailPengajuan->jadwal_kerja = $jadwalKerjaArray[$key] ?? null;
                $detailPengajuan->pekerjaan_yang_dilakukan = $pekerjaanArray[$key] ?? null;
                $detailPengajuan->keterangan = $keteranganArray[$key] ?? null;
                $detailPengajuan->tul = $tulArray[$key] ?? null;
                $detailPengajuan->planning_status = $planningStatusArray[$key] ?? 'draft';
                $detailPengajuan->actual_status = $actualStatusArray[$key] ?? 'draft';
                $detailPengajuan->reject_reason = $rejectReasonArray[$key] ?? null;
                $detailPengajuan->current_approver = $currentApproverArray[$key] ?? Auth::user()->npk;
                $detailPengajuan->action = $$actionArray[$key] ?? null;
                $detailPengajuan->save();

                $planOvertime = new PlanOvertimeModel();
                $planOvertime->detail_pengajuan_id = $detailPengajuan->id;
                $planOvertime->waktu_mulai = $waktuMulaiArray[$key] ?? null;
                $planOvertime->waktu_selesai = $waktuSelesaiArray[$key] ?? null;
                $planOvertime->planning_waktu = $planningWaktuArray[$key] ?? null;
                $planOvertime->save();
            }

            // Commit the transaction
            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan lembur berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback the transaction if something failed
            DB::rollback();
            Log::error('Error memperbarui data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
