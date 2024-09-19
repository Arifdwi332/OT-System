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
    ];

    // Relasi dengan department
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'department_id');
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
