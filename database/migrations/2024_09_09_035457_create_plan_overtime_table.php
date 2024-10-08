<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanOvertimeTable extends Migration
{
    public function up()
    {
        Schema::create('plan_overtime', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_pengajuan_id');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('planning_waktu');
            $table->timestamps();

            $table->foreign('detail_pengajuan_id')->references('id')->on('detail_pengajuan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_overtime');
    }
}
