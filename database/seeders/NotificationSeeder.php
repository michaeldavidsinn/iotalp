<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['system', 'buzzer', 'access', 'light'];

        foreach (range(1, 10) as $i) {
            DB::table('notifications')->insert([
                'title' => "Contoh Notifikasi #$i",
                'message' => "Ini adalah pesan dummy untuk testing notifikasi.",
                'type' => $types[array_rand($types)],
                'important' => rand(0, 1),
                'is_read' => false,
                'data' => json_encode([
                    'sensor' => 'rfid',
                    'value' => rand(1, 100),
                    'device' => 'Arduino-1'
                ]),
                'created_at' => now()->subMinutes($i * 3),
                'updated_at' => now()->subMinutes($i * 3),
            ]);
        }
    }
}
