<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'tanggal',
        'department_id',
        'current_status',
        'current_approver',
        'pengajuan_status',
    ];

    // Relasi dengan department
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'department_id');
    }

    // Relasi dengan karyawan (current approver)
    public function currentApprover()
    {
        return $this->belongsTo(KaryawanModel::class, 'current_approver', 'npk');
    }

    // Relasi dengan detail pengajuan
    public function detailPengajuan()
    {
        return $this->hasMany(DetailPengajuanModel::class, 'pengajuan_id');
    }

    // Relasi dengan log pengajuan
    public function logPengajuan()
    {
        return $this->hasMany(LogPengajuanModel::class, 'pengajuan_id');
    }
}
