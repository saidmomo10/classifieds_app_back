<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaySubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'name' => 'Medium',
                'price' => 5000,
                'max_images' => 5,
                'max_ads' => 5,
                'duration' => 20,
                'type' => 'Gratuit',
                'description' => "C'est gratuit",
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}