<?php

use App\Models\Country;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('dial_code', '6');
            $table->string('country_code', '6');
            $table->integer('phone_length')->default(10);
            $table->string('flag_url')->nullable();
            $table->timestamps();
        });
        
        Country::create([
            'name'         => 'Ethiopia',
            'dial_code'    => '+251',
            'phone_length' => 9,
            'country_code' => 'ET',
            'flag_url'     => 'flags/et.svg'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
