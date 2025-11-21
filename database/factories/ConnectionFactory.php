<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scanner_profile_id' => Profile::factory(),
            'scanned_profile_id' => Profile::factory(),
            'notes' => fake()->optional()->text(500),
            'met_at' => now(),
        ];
    }

    public function withNotes(?string $notes = null): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes ?? fake()->text(500),
        ]);
    }
}
