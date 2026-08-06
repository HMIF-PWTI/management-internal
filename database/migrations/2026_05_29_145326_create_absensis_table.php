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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cek_kartu_id')
                ->constrained('cek_kartus')
                ->cascadeOnDelete();

            $table->foreignId('rfid_card_id')
                ->constrained('rfid_cards')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('kegiatan_id')
                ->nullable()
                ->constrained('kegiatans')
                ->nullOnDelete();

            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();

            $table->enum('status', ['hadir', 'pulang'])->default('hadir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
