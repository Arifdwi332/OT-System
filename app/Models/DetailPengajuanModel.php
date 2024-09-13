<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanModel extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan';

    protected $fillable = [
        'pengajuan_id',
        'npk',
        'jadwal_kerja',
        'pekerjaan_yang_dilakukan',
        'keterangan',
        'tul',
        'dpstatus',
    ];

    // Relasi dengan pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }

    // Relasi dengan karyawan
    public function karyawan()
    {
        return $this->belongsTo(KaryawanModel::class, 'npk', 'npk');
    }

    // Relasi dengan plan_overtime
    public function planOvertime()
    {
        return $this->hasOne(PlanOvertimeModel::class, 'detail_pengajuan_id');
    }

    // Relasi dengan actual_overtime
    public function actualOvertime()
    {
        return $this->hasOne(ActualOvertimeModel::class, 'detail_pengajuan_id');
    }
}

