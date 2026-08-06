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
    Schema::create('rfid_cards', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->foreignId('divisi_id')
                    ->nullable()
                    ->constrained('divisis')
                    ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_cards');
    }
};
