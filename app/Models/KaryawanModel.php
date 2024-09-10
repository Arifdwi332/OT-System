<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class KaryawanModel extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'karyawan';

    protected $primaryKey = 'npk';

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'role' => 'string',
    ];

    protected $fillable = [
        'npk',
        'nama',
        'password',
        'department_id',
        'no_telp',
        'role',
    ];

    // Jika Anda menggunakan timestamps
    public $timestamps = true;

    // Set default role jika belum diset
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->role)) {
                $model->role = 'karyawan'; // Role default jika tidak diatur
            }
        });
    }
    // Relasi dengan department
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'department_id');
    }

    // Relasi dengan pengajuan (untuk current approver)
    public function pengajuanAsApprover()
    {
        return $this->hasMany(PengajuanModel::class, 'current_approver', 'npk');
    }
}
