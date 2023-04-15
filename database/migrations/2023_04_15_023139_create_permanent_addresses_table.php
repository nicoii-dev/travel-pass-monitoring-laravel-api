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
        Schema::create('permanent_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->string('street');
            $table->string('barangay');
            $table->string('city_municipality');
            $table->string('province');
            $table->string('region');
            $table->string('zipcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permanent_addresses');
    }
};
