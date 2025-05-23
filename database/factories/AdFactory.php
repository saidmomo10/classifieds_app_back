<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use App\Models\Department;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->words(3, true),
            'price' => $this->faker->optional()->randomFloat(2, 1000, 100000),
            'price_type' => $this->faker->randomElement(['Fixe', 'Débattable']),
            'state' => $this->faker->randomElement(['Neuf', 'Usé']),
            'sold' => $this->faker->randomElement(['En cours de vente', 'Vendu']),
            'delivery_status' => $this->faker->randomElement(['Oui', 'Non']),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory()->create()->id,
            'phone' => $this->faker->phoneNumber(),
            'views' => $this->faker->numberBetween(0, 1000),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'subcategory_id' => Subcategory::inRandomOrder()->first()->id ?? Subcategory::factory()->create()->id,
        ];
    }
}
