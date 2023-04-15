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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('dob');
            $table->string('phone_number');
            $table->string('role');
            $table->string('status');
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('is_verified')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

            // Insert some stuff
            DB::table('users')->insert(
                array(
                    'id' => '1',
                    'first_name' => 'admin',
                    'middle_name' => 'admin',
                    'last_name' => 'admin',
                    'role' => 'admin',
                    'gender' => 'male',
                    'dob' => '1990/01/01',
                    'phone_number' => '09354135541',
                    'status' => '1',
                    'is_verified' => '1',
                    'email' => 'admin@admin.com',
                    'password' => bcrypt('Default123'),
                )
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
