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
            'max_workers' => $this->faker->numberBetween(1, 10),
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'uuid',
                'name',
                'description',
                'max_workers',
            ])->getContent();

        $response = json_decode($response, true);
        $this->assertEquals('active', $response['state_code']);
        $this->assertDatabaseHas('clients', [
            'uuid' => $response['uuid'],
            'name' => $response['name'],
            'description' => $response['description'],
            'max_workers' => $response['max_workers'],
            'state_code' => $response['state_code'],
        ], 'mongodb');
    }

    public function testUpdateClient()
    {
        $response = $this->postJson('/api/clients', [
            'name' => $this->faker->text,
            'description' => $this->faker->text,
            'max_workers' => $this->faker->numberBetween(1, 10),
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'uuid',
                'name',
                'description',
                'max_workers',
            ])->getContent();

        $response = json_decode($response, true);

        $response = $this->putJson('/api/clients/' . $response['uuid'], [
            'name' => 'Updated'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'uuid',
                'name',
                'description',
                'max_workers',
            ])->getContent();

        $response = json_decode($response, true);

        $this->assertEquals('Updated', $response['name']);

        $this->assertDatabaseHas('clients', [
            'uuid' => $response['uuid'],
            'name' => $response['name'],
            'description' => $response['description'],
            'max_workers' => $response['max_workers'],
            'state_code' => $response['state_code'],
        ], 'mongodb');
    }
}
