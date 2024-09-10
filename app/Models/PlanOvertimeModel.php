<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanOvertimeModel extends Model
{
    use HasFactory;

    protected $table = 'plan_overtime';

    protected $fillable = [
        'detail_pengajuan_id',
        'waktu_mulai',
        'waktu_selesai',
        'total_waktu',
    ];

    protected $casts = [
        'waktu_mulai' => 'time',
        'waktu_selesai' => 'time',
        'total_waktu' => 'integer',
    ];

    // Relasi dengan detail_pengajuan
    public function detailPengajuan()
    {
        return $this->belongsTo(DetailPengajuanModel::class, 'detail_pengajuan_id');
    }
}

