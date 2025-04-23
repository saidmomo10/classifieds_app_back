<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des seeders spécifiques
        $this->call([
            PermissionTableSeeder::class,
            CategoryTableSeeder::class,
            SubCategoryTableSeeder::class,
            FreeSubscriptionTableSeeder::class,
            CreateAdminUserSeeder::class,
            DepartmentSeeder::class,
            // AdSeeder::class,
        ]);
    }
}
