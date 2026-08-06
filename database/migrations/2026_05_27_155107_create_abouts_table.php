<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();

            $table->string('foto')->nullable();
            $table->string('nama');
            $table->string('jabatan');

            $table->foreignId('id_divisi')
                ->nullable()
                ->constrained('divisis')
                ->nullOnDelete();

            $table->integer('urutan')->default(0);

            $table->boolean('is_inti')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};