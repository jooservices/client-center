<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'queues' => [
                [
                    'name' => $this->faker->word,
                    'workers' => $this->faker->numberBetween(1, 10),
                ],
            ],
            'ip' => $this->faker->ipv4,
            'state_code' => 'active',
        ];
    }
}
