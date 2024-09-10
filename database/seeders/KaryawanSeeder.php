<?php

namespace Database\Seeders;

use App\Models\KaryawanModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawanData = [
            [
                'npk' => '0001',
                'nama' => 'arif superadmin',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',  // Jika nomor telepon diperlukan
                'role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0002',
                'nama' => 'arif admin',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0003',
                'nama' => 'arif karyawan',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0004',
                'nama' => 'arif sechead',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'section_head',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0005',
                'nama' => 'arif dephead',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'department_head',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0006',
                'nama' => 'arif divhead',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'division_head',
                'created_at' => now(),
                'updated_at' => now()
             ],
             [
                'npk' => '0007',
                'nama' => 'arif hrd',
                'password' => bcrypt('1234'),
                'department_id' => '1',
                'no_telp' => '081329964278',
                'role' => 'hrd',
                'created_at' => now(),
                'updated_at' => now()
             ],
        ];
        foreach($karyawanData as $key => $val) {
            KaryawanModel::create($val);
        }
    }
}
