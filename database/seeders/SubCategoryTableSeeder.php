<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_categories')->insert([
            [
                'name' => 'Meubles',
                'icone' => 'icones/canap.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Voiture',
                'icone' => 'icones/car.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Education/Formation',
                'icone' => 'icones/education.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bar/Fast-food',
                'icone' => 'icones/food.jpg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Santé',
                'icone' => 'icones/health.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Immobilier',
                'icone' => 'icones/house.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Produits vivriers',
                'icone' => 'icones/market.jpg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Téléphone',
                'icone' => 'icones/phone.jpg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mode et vêtement',
                'icone' => 'icones/vêtements.jpg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Appareil électronique',
                'icone' => 'icones/canap.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Appareil électroménager',
                'icone' => 'icones/electro.png', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
