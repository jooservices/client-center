<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
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
            'service' => Str::slug($this->faker->name),
            'url' => $this->faker->url,
            'requestOptions' => [
                'headers' => [
                    'User-Agent' => $this->faker->userAgent,
                ],
            ],
            'options' => [
                'maxRetries' => 3,
                'retryDelay' => 1000,
                'priority' => 1,
            ],
            'state_code' => 'init',
        ];
    }
}
