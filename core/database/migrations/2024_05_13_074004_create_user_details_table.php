<?php

use App\Models\Address;
use App\Models\User;
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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignIdFor(Address::class)->nullable();
            $table->string('id_front')->nullable();
            $table->string('id_back')->nullable();
            $table->string('id_verification_status', 20)->nullable();
            $table->string('address_verification_status', 20)->nullable();
            $table->string('decline_reasons', 400)->nullable();
            $table->string('gender', 15)->nullable();
            $table->integer('age')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->foreignIdFor(User::class, 'emergency_user_id')->nullable();
            $table->string('emmergency_contact')->nullable();
            $table->foreignIdFor(Address::class, 'emergency_address_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
