<?php

namespace Tests\Unit\Services\Client;

use App\Services\Client\Factory;
use GuzzleHttp\Psr7\Request;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    public function testEnableMockingSuccess() {
        $client = app(Factory::class)
            ->enableMocking()
            ->addMockResponse(200)
            ->enableRetries(1)
            ->make();

        $response = $client->request('GET', $this->faker->url);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMockWithException() {
        $client = app(Factory::class)
            ->enableMocking()
            ->addMockRequestException('Error', new Request('GET', $this->faker->url))
            ->enableRetries(1)
            ->make();

        $this->expectException(\Exception::class);
        $client->request('GET', $this->faker->url);
    }
}
