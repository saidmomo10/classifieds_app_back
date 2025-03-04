<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class AdSeeder extends Seeder
{
    public function run()
    {
        Ad::factory(10)->create()->each(function ($ad) {
            // Générer des images pour chaque annonce
            Image::factory(2)->create([
                'ad_id' => $ad->id,
                'path' => 'images/' . \Illuminate\Support\Str::random(20) . '.jpg',
            ]);
        });
    }
}
