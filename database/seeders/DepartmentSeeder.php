<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Atlantique/Littoral',
                'image' => 'departments/atlantique.jpg',
                'cities' => ['Cotonou', 'Abomey-Calavi', 'Ouidah']
            ],
            [
                'name' => 'Borgou/Alibori',
                'image' => 'departments/borgou.jpg',
                'cities' => ['Parakou', 'NDali', 'Kandi']
            ],
            [
                'name' => 'Ouémé/Plateau',
                'image' => 'departments/oueme.jpeg',
                'cities' => ['Porto-Novo', 'Sèmè-Kpodji']
            ],
            [
                'name' => 'Zou/Colline',
                'image' => 'departments/zou.jpg',
                'cities' => ['Abomey', 'Bohicon', 'Savè']
            ],
            [
                'name' => 'Atacora/Donga',
                'image' => 'departments/atacora.png',
                'cities' => ['Djougou', 'Natitingou', 'Tanguiéta']
            ],
        ];

        foreach ($departments as $data) {
            $department = Department::create([
                'name' => $data['name'],
                'image' => $data['image']
            ]);

            foreach ($data['cities'] as $cityName) {
                City::create([
                    'name' => $cityName,
                    'department_id' => $department->id
                ]);
            }
        }
    }
}
