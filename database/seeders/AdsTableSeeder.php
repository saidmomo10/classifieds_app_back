<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ads')->insert([
            [
                'title' => 'Meubles',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou',
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Voiture',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Education/Formation',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Bar/Fast-food',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Santé',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Immobilier',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Produits vivriers',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Téléphone',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Mode et vêtement',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Appareil électronique',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou', // Chemin de l'icône
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Appareil électroménager',
                'description' => 'Meubles',
                'state' => 'Usé',
                'price' => 8000,
                'city' => 'Cotonou',
                'user_id' => 3, // Associer à une catégorie existante
                'created_at' => Carbon::now(),  // Timestamp actuel
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
