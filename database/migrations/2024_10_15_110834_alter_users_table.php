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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']); // Drop the unique constraint on the 'phone' column
            $table->dropUnique(['email']); // Drop the unique constraint on the 'email' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email'); // Restore the unique constraint on the 'email' column
            $table->unique('phone'); // Restore the unique constraint on the 'phone' column
        });
    }
};
