<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        return [
            'ad_id' => null, // Sera défini dans le seeder
            'path' => 'icones/canap.png',
        ];
    }
}
