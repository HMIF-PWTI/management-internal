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
        Schema::create('lapak_kwus', function (Blueprint $table) {
            $table->id();
            $table->string('gambar')->nullable();
            $table->string('nama_produk');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->string('link');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapak_kwus');
    }
};
