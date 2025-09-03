<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $species = $this->faker->randomElement(['dog', 'cat', 'rabbit', 'bird']);
        $breeds = [
            'dog' => ['Labrador', 'Golden Retriever', 'German Shepherd', 'Bulldog', 'Poodle', 'Mixed Breed'],
            'cat' => ['Persian', 'Siamese', 'Maine Coon', 'British Shorthair', 'Ragdoll', 'Mixed Breed'],
            'rabbit' => ['Holland Lop', 'Netherland Dwarf', 'Lionhead', 'Mini Rex', 'Mixed Breed'],
            'bird' => ['Parakeet', 'Cockatiel', 'Canary', 'Lovebird', 'Finch']
        ];

        return [
            'name' => $this->faker->firstName(),
            'species' => $species,
            'breed' => $this->faker->randomElement($breeds[$species]),
            'age' => $this->faker->numberBetween(1, 15),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'description' => $this->faker->paragraph(3),
            'medical_history' => $this->faker->optional(0.7)->paragraph(),
            'adoption_status' => $this->faker->randomElement(['available', 'pending', 'adopted']),
            'featured' => $this->faker->boolean(20), // 20% chance of being featured
            'images' => $this->faker->optional(0.8)->randomElements([
                '/animals-rescue/animal1.jpg',
                '/animals-rescue/animal2.jpg',
                '/animals-rescue/animal3.jpg',
                '/animals-rescue/animal4.jpg',
            ], $this->faker->numberBetween(1, 3)),
        ];
    }

    /**
     * Indicate that the animal is available for adoption.
     */
    public function available()
    {
        return $this->state(fn (array $attributes) => [
            'adoption_status' => 'available',
        ]);
    }

    /**
     * Indicate that the animal is featured.
     */
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }
}
