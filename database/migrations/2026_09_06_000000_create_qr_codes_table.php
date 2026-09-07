<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('link');
            $table->string('logo_path')->nullable();
            $table->string('qr_color', 7)->default('#0f0606');
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('frame')->default('scan');
            $table->string('shape')->default('square');
            $table->text('qr_image_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
