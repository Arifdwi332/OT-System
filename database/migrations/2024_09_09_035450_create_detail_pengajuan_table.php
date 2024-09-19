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
            $table->enum('planning_status', [
                'pending',
                'approved by section head',
                'approved by department head',
                'approved by division head',
                'approved by hrd',
                'rejected by section head',
                'rejected by department head',
                'rejected by division head',
                'rejected by hrd'
            ])->nullable();
            $table->enum('actual_status', [
                'pending',
                'approved by section head',
                'approved by department head',
                'approved by division head',
                'approved by hrd',
                'rejected by section head',
                'rejected by department head',
                'rejected by division head',
                'rejected by hrd'
            ])->nullable();
            $table->string('reject_reason')->nullable();
            $table->unsignedBigInteger('current_approver');
            $table->enum('dp_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('tul')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
            $table->foreign('npk')->references('npk')->on('karyawan')->onDelete('cascade');
            $table->foreign('current_approver')->references('npk')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pengajuan');
    }
}
