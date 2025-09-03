<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteer>
 */
class VolunteerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $areasOfInterest = [
            'Animal Care',
            'Dog Walking',
            'Cat Socialization',
            'Administrative Support',
            'Event Planning',
            'Fundraising',
            'Photography',
            'Transportation',
            'Foster Care',
            'Veterinary Assistance'
        ];

        $availability = [
            'Monday Morning',
            'Monday Afternoon',
            'Tuesday Morning',
            'Tuesday Afternoon',
            'Wednesday Morning',
            'Wednesday Afternoon',
            'Thursday Morning',
            'Thursday Afternoon',
            'Friday Morning',
            'Friday Afternoon',
            'Saturday Morning',
            'Saturday Afternoon',
            'Sunday Morning',
            'Sunday Afternoon'
        ];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'areas_of_interest' => $this->faker->randomElements($areasOfInterest, $this->faker->numberBetween(1, 4)),
            'availability' => $this->faker->randomElements($availability, $this->faker->numberBetween(2, 6)),
            'experience' => $this->faker->optional(0.7)->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'active', 'inactive']),
            'applied_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the volunteer is pending approval.
     */
    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'applied_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the volunteer is active.
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
