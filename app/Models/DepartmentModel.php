<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $fillable = [
        'nama',
        'division_id',
    ];

    // Relasi dengan divisi
    public function division()
    {
        return $this->belongsTo(DivisionModel::class, 'division_id');
    }

    // Relasi dengan karyawan
    public function karyawan()
    {
        return $this->hasMany(KaryawanModel::class, 'department_id');
    }

    // Relasi dengan pengajuan
    public function pengajuan()
    {
        return $this->hasMany(PengajuanModel::class, 'department_id');
    }
}

