<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lkmm_pendaftarans')) {
            Schema::create('lkmm_pendaftarans', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_registrasi')->unique();
                $table->string('nama_lengkap');
                $table->string('nim');
                $table->string('angkatan');
                $table->string('kelas');
                $table->decimal('ipk', 3, 2);
                $table->string('no_wa');
                $table->string('email');
                $table->string('file_formulir')->nullable();
                $table->string('file_pernyataan')->nullable();
                $table->string('file_izin_ortu')->nullable();
                $table->string('file_cv')->nullable();
                $table->string('file_surat_aktif')->nullable();
                $table->string('file_kulber')->nullable();
                $table->string('file_transkrip')->nullable();
                $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lkmm_pendaftarans');
    }
};
