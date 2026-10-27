<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_penerima');
            $table->string('email_penerima');
            $table->string('judul');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->date('jatuh_tempo')->nullable();
            $table->string('status')->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamp('terakhir_dikirim_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
