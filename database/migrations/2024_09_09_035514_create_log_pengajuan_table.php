<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPengajuanTable extends Migration
{
    public function up()
    {
        Schema::create('log_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('action'); // e.g., 'submitted', 'approved', 'rejected'
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_pengajuan');
    }
}
