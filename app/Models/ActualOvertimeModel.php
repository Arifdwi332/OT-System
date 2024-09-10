<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualOvertimeModel extends Model
{
    use HasFactory;

    protected $table = 'actual_overtime';

    protected $fillable = [
        'detail_pengajuan_id',
        'waktu_mulai',
        'waktu_selesai',
        'total_waktu',
    ];

    // Relasi dengan detail_pengajuan
    public function detailPengajuan()
    {
        return $this->belongsTo(DetailPengajuanModel::class, 'detail_pengajuan_id');
    }
}
