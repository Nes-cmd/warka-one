<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_codes', function (Blueprint $table) {
            $table->string('session_id', 255)->nullable()->after('candidate');
            $table->index(['candidate', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::table('verification_codes', function (Blueprint $table) {
            $table->dropIndex(['candidate', 'session_id']);
            $table->dropColumn('session_id');
        });
    }
};
