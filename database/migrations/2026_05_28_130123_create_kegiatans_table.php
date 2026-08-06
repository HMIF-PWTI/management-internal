<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('gambar')->nullable();
            $table->string('nama');
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->string('lokasi')->nullable();

            $table->boolean('is_absen_active')->default(false);
            $table->boolean('is_web_active')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
