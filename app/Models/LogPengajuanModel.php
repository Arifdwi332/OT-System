<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPengajuanModel extends Model
{
    use HasFactory;

    protected $table = 'log_pengajuan';

    protected $fillable = [
        'pengajuan_id',
        'action',
        'remarks'
    ];

    // Relasi dengan pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanModel::class, 'pengajuan_id');
    }
}

