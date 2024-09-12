<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPengajuanTable extends Migration
{
    public function up()
    {
        Schema::create('detail_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('npk');
            $table->enum('jadwal_kerja', ['shift 1', 'shift 2', 'shift 3']);
            $table->string('pekerjaan_yang_dilakukan');
            $table->string('keterangan')->nullable();
            $table->integer('tul')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
            $table->foreign('npk')->references('npk')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pengajuan');
    }
}
