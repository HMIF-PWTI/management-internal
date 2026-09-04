<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hmif_forms', function (Blueprint $table): void {
            $table->boolean('collect_email')->default(false)->after('require_login');
            $table->boolean('limit_one_response')->default(false)->after('collect_email');
            $table->boolean('show_progress_bar')->default(false)->after('limit_one_response');
            $table->boolean('shuffle_questions')->default(false)->after('show_progress_bar');
            $table->boolean('required_by_default')->default(false)->after('shuffle_questions');
            $table->text('confirmation_message')->nullable()->after('required_by_default');
            $table->timestamp('opens_at')->nullable()->after('confirmation_message');
            $table->timestamp('closes_at')->nullable()->after('opens_at');
        });
    }

    public function down(): void
    {
        Schema::table('hmif_forms', function (Blueprint $table): void {
            $table->dropColumn(['collect_email', 'limit_one_response', 'show_progress_bar', 'shuffle_questions', 'required_by_default', 'confirmation_message', 'opens_at', 'closes_at']);
        });
    }
};
