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
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');       // Suhu (DHT11/BMP280)
            $table->float('humidity');          // Kelembapan (DHT11)
            $table->float('pressure');          // Tekanan (BMP280)
            $table->float('altitude');          // Ketinggian (BMP280)
            $table->float('door_distance');     // Jarak Ultrasonik
            $table->timestamps();             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
