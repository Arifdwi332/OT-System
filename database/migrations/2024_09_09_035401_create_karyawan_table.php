<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->unsignedBigInteger('npk')->primary();  // NPK dari API
            $table->string('nama');                        // Nama dari API
            $table->string('password');                    // Password dari API, di-hash
            $table->unsignedBigInteger('department_id');   // Department ID dari API
            $table->string('no_telp')->nullable();         // Kolom nomor telepon untuk notifikasi WhatsApp
            $table->enum('role', ['superadmin', 'admin', 'section_head', 'department_head', 'division_head', 'hrd', 'karyawan'])->default('karyawan');  // Role default 'karyawan'
            $table->timestamps();
        
            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}
