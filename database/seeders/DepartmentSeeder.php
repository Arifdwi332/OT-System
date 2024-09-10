<?php

namespace Database\Seeders;

use App\Models\DepartmentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $DepartmentData = [
            [
                'nama' => 'Vendor',
                'division_id' => '1'
            ],
            [
                'nama' => 'Production',
                'division_id' => '1'
            ],
            [
                'nama' => 'Marketing',
                'division_id' => '2'
            ],
            [
                'nama' => 'Engginering',
                'division_id' => '2'
            ],
            [
                'nama' => 'Mold & Tooling',
                'division_id' => '2'
            ],
        ];
        foreach($DepartmentData as $key => $val){
            DepartmentModel::create($val);
        }
    }
}
