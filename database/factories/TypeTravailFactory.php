<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeTravail>
 */
class TypeTravailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            ['type_work' => 'Transport Maritime', 'description' => 'Transport de marchandises par voie maritime'],
            ['type_work' => 'Transport Terrestre', 'description' => 'Transport de marchandises par voie terrestre'],
            ['type_work' => 'Transport Aérien', 'description' => 'Transport de marchandises par voie aérienne'],
        ];
        
        $randomType = $this->faker->randomElement($types);
        
        return [
            'type_work' => $randomType['type_work'],
            'description' => $randomType['description'],
        ];
    }
}
