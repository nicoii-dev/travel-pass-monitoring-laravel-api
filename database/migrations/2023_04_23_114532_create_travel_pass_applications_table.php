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
        Schema::create('travel_pass_applications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('reservation_id');
            $table->string('travel_type');
            $table->date('date_of_travel');
            $table->string('status');
            $table->string('origin_region');
            $table->string('origin_province');
            $table->string('origin_city');
            $table->string('origin_barangay');
            $table->string('origin_street');
            $table->string('origin_zipcode');
            $table->string('destination_region');
            $table->string('destination_province');
            $table->string('destination_city');
            $table->string('destination_barangay');
            $table->string('destination_street');
            $table->string('destination_zipcode');
            $table->string('reference_code')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_pass_applications');
    }
};
