<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    public function testRegisterClient()
    {
        $response = $this->postJson('/api/clients', [
            'name' => $this->faker->text,
            'description' => $this->faker->text,
            'queues' => [
                [
                    'name' => $this->faker->text,
                    'workers' => $this->faker->numberBetween()
                ]
            ],
            'ip' => $this->faker->ipv4,
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'uuid',
                'name',
                'queues',
            ])->getContent();

        $response = json_decode($response, true);
        $this->assertEquals('active', $response['state_code']);
        $this->assertDatabaseHas('clients', [
            'uuid' => $response['uuid'],
            'name' => $response['name'],
            'description' => $response['description'],
            'state_code' => $response['state_code'],
        ], 'mongodb');
    }

    public function testUpdateClient()
    {
        $response = $this->postJson('/api/clients', [
            'name' => $this->faker->text,
            'description' => $this->faker->text,
            'queues' => [
                [
                    'name' => $this->faker->text,
                    'workers' => $this->faker->numberBetween()
                ]
            ],
            'ip' => $this->faker->ipv4,
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'uuid',
                'name',
                'queues',
            ])->getContent();

        $response = json_decode($response, true);

        $response = $this->putJson('/api/clients/' . $response['uuid'], [
            'name' => 'Updated'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'uuid',
                'name',
                'queues',
            ])->getContent();

        $response = json_decode($response, true);

        $this->assertEquals('Updated', $response['name']);

        $this->assertDatabaseHas('clients', [
            'uuid' => $response['uuid'],
            'name' => $response['name'],
            'description' => $response['description'],
            'state_code' => $response['state_code'],
        ], 'mongodb');
    }
}
