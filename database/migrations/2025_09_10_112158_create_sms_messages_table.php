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
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->string('smsable_id'); // Creates smsable_id and smsable_type columns
            $table->string('smsable_type');
            $table->string('phone_number');
            $table->text('message');
            $table->string('status')->default('pending'); // pending, sent, delivered, failed
            $table->string('provider')->default('afro'); // afro, faris
            $table->string('campaign')->nullable();
            $table->string('message_id')->nullable(); // Provider's message ID
            $table->json('response_data')->nullable(); // Store API response
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->index(['smsable_id', 'smsable_type']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
