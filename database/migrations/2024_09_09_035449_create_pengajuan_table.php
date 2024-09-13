<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanTable extends Migration
{
    public function up()
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger('department_id');
            $table->enum('current_status', [
                'pending',
                'approved by section head',
                'approved by department head',
                'approved by division head',
                'approved by hrd',
                'rejected by section head',
                'rejected by department head',
                'rejected by division head',
                'rejected by hrd'
            ])->default('pending');
            $table->unsignedBigInteger('current_approver');
            $table->enum('pengajuan_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
            $table->foreign('current_approver')->references('npk')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan');
    }
}
