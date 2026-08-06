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
      Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nim');
            $table->string('password');
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('id_divisi')
                ->nullable()
                ->constrained('divisis')
                ->nullOnDelete();
            $table->enum('status_keanggotaan', [
                'aktif',
                'nonaktif'
            ])->default('nonaktif');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};