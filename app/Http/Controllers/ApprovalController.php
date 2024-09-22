<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPengajuanModel;
use App\Models\PengajuanModel;
use App\Models\PlanOvertimeModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    // Menampilkan halaman approval planning
    public function showPlanningApproval()
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');

        // Mengambil data pengajuan, detail_pengajuan, dan plan_overtime
        $pengajuanData = PengajuanModel::with(['department', 'detailPengajuan.planOvertime'])
            ->get()
            ->map(function ($pengajuan) {
                return [
                    'id' => $pengajuan->id,
                    'tanggal' => $pengajuan->tanggal,
                    'department' => $pengajuan->department->nama,
                    'hari' => Carbon::parse($pengajuan->tanggal)->translatedFormat('l'),
                    'jumlah_member' => $pengajuan->detailPengajuan->count(),
                    'members' => $pengajuan->detailPengajuan->map(function ($detail) {
                        return [
                            'npk' => $detail->npk,
                            'jadwal_kerja' => $detail->jadwal_kerja,
                            'pekerjaan_yang_dilakukan' => $detail->pekerjaan_yang_dilakukan,
                            'waktu_mulai' => $detail->planOvertime->waktu_mulai,
                            'waktu_selesai' => $detail->planOvertime->waktu_selesai,
                            'planning_waktu' => optional($detail->planOvertime)->planning_waktu, // Ambil dari planOvertime
                            'keterangan' => $detail->keterangan,
                            'planning_status' => $detail->planning_status
                        ];
                    })
                ];
            });

        // Mendapatkan role pengguna saat ini
        $userRole = Auth::user()->role;

        return view('approval.aplanning', compact('pengajuanData', 'userRole'));
    }
    public function approvePlanning(Request $request, $id)
    {
        // Ambil data pengajuan berdasarkan ID
        $detailPengajuan = DetailPengajuanModel::where('pengajuan_id', $id)->get();

        foreach ($detailPengajuan as $detail) {
            $dpstatus = $request->input('dp_status.' . $detail->npk);
            $rejectReason = $request->input('reject_reason.' . $detail->npk);

            // Jika statusnya 'approved' atau 'rejected'
            if ($dpstatus === 'approved') {
                $detail->planning_status = 'approved';

                // Cek apakah approver adalah 'section_head' dan dp_status 'approved'
                if (Auth::user()->npk == $detail->current_approver && Auth::user()->role == 'section_head' && $detail->dp_status == 'approved') {
                    $detail->planning_status = 'approved by section head';
                }
            } elseif ($dpstatus === 'rejected') {
                $detail->planning_status = 'rejected';
                $detail->reject_reason = $rejectReason;
            }
            dd($detail->planning_status);
            // Simpan perubahan
            $detail->save();
        }

        return redirect()->route('approval.planning')->with('success', 'Planning status updated successfully.');
    }

}
