<?php

namespace Database\Seeders;

use App\Models\DivisionModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisionData = [
            [
                'nama' => 'Divisi Planning'
            ],
            [
                'nama' => 'Divisi Bisnis Development dan Engginering'
            ],
        ];
        foreach($divisionData as $key => $val) {
            DivisionModel::create($val);
        };
    }
}
