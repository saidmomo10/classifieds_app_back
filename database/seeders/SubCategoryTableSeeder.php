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
                'icone' => 'icones/iconizer-furniture.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Voitures',
                'icone' => 'icones/iconizer-car.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Formations',
                'icone' => 'icones/iconizer-education.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bar/Fast-food',
                'icone' => 'icones/food.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Santé',
                'icone' => 'icones/iconizer-hospital.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Immobilier',
                'icone' => 'icones/iconizer-real-estate.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Produits vivriers',
                'icone' => 'icones/market.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Téléphones',
                'icone' => 'icones/phone.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mode et vêtements',
                'icone' => 'icones/iconizer-tshirt.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Appareils électroniques',
                'icone' => 'icones/iconizer-laptop.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Jobs',
                'icone' => 'icones/iconizer-jobs.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sports',
                'icone' => 'icones/sports.svg', // Chemin de l'icône
                'category_id' => 1, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
