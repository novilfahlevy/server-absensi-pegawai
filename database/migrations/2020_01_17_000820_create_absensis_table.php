<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->time('absensi_masuk');
            $table->time('absensi_keluar')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status');
            $table->string('foto_absensi_masuk');
            $table->string('foto_absensi_keluar')->nullable();
            $table->string('latitude_absen_masuk');
            $table->string('longitude_absen_masuk');
            $table->string('latitude_absen_keluar');
            $table->string('longitude_absen_keluar');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensis');
    }
}
