<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amounts = [25, 50, 75, 100, 150, 200, 250, 500, 1000];
        $paymentMethods = ['stripe', 'paypal', 'credit_card', 'bank_transfer'];
        
        return [
            'donor_name' => $this->faker->name(),
            'donor_email' => $this->faker->safeEmail(),
            'amount' => $this->faker->randomElement($amounts),
            'type' => $this->faker->randomElement(['one-time', 'recurring']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'transaction_id' => $this->faker->optional(0.8)->regexify('[A-Z0-9]{10,15}'),
        ];
    }

    /**
     * Indicate that the donation is completed.
     */
    public function completed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'transaction_id' => $this->faker->regexify('[A-Z0-9]{12}'),
        ]);
    }

    /**
     * Indicate that the donation is recurring.
     */
    public function recurring()
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'recurring',
        ]);
    }

    /**
     * Create a large donation.
     */
    public function large()
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomElement([500, 750, 1000, 1500, 2000, 5000]),
        ]);
    }
}
