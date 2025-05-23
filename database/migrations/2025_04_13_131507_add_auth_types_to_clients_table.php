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
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->json('use_auth_types')->default('["email", "phone"]')->after('revoked');
            $table->boolean('registration_enabled')->default(false)->after('use_auth_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->dropColumn('use_auth_types');
            $table->dropColumn('registration_enabled');
        });
    }
};
