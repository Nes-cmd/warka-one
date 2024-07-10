<?php

use App\Models\SubCity;
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
        Schema::create('woredas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lat', 40)->nullable();
            $table->string('lng', 40)->nullable();
            $table->foreignIdFor(SubCity::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woredas');
    }
};
