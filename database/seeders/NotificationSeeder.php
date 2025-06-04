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
        for ($i = 0; $i < 10; $i++) {
            DB::table('notifications')->insert([
                'title' => 'Notifikasi #' . ($i + 1),
                'message' => 'Pesan dummy untuk testing UI notifikasi.',
                'is_read' => false,
                'created_at' => now()->subMinutes($i * 5),
                'updated_at' => now()->subMinutes($i * 5),
            ]);
        }
    }
}
