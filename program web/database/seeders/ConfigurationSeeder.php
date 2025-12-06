<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configurations')->insert([
            'fan_temp_threshold' => 30.0,
            'door_dist_threshold' => 15.0,
            'schedule_open' => '08:00:00',
            'schedule_close' => '17:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
