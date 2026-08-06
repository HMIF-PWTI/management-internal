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
       Schema::create('cek_kartus', function (Blueprint $table) {
            $table->id();
            $table->string('uid');

            $table->foreignId('rfid_card_id')
                ->nullable()
                ->constrained('rfid_cards')
                ->nullOnDelete();

            $table->enum('status', ['unknown', 'inactive', 'valid'])
                ->default('unknown');

            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_kartus');
    }
};
