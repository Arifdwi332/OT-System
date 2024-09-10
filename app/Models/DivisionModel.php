<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionModel extends Model
{
    use HasFactory;

    protected $table = 'division';

    protected $fillable = [
        'nama',
    ];

    // Relasi dengan department
    public function departments()
    {
        return $this->hasMany(DepartmentModel::class, 'division_id');
    }
}
