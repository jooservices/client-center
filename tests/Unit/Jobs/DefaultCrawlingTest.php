<?php

namespace Tests\Unit\Jobs;

use App\Jobs\DefaultCrawling;
use App\Models\Request;
use App\Services\Client\Client;
use App\Services\Client\Factory;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class DefaultCrawlingTest extends TestCase
{
    public function testDefaultCrawlingSuccess()
    {
        $url = $this->faker->url;

        $this->instance(
            Factory::class,
            Mockery::mock(Factory::class, function (MockInterface $mock) use ($url) {
                $mock->shouldReceive('make')
                    ->andReturn(
                        Mockery::mock(Client::class, function (MockInterface $mock) use ($url) {
                            $mock->shouldReceive('request')
                                ->withSomeOfArgs('GET', $url)
                                ->andReturn(
                                    new Response(
                                        200,
                                        [],
                                        'Hello World'
                                    )
                                );
                        })
                    );
            })
        );

        $request = Request::factory()
            ->create([
                'url' => $url,
                'requestOptions' => [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ],
            ]);

        DefaultCrawling::dispatch($request);

        $request->refresh();
        $this->assertEquals('Hello World', $request->response);
        $this->assertEquals('request-completed', $request->state_code);
    }

    public function testDefaultCrawlingFailed()
    {
        $url = $this->faker->url;

        $this->instance(
            Factory::class,
            Mockery::mock(Factory::class, function (MockInterface $mock) use ($url) {
                $mock->shouldReceive('make')
                    ->andReturn(
                        Mockery::mock(Client::class, function (MockInterface $mock) use ($url) {
                            $mock->shouldReceive('request')
                                ->withSomeOfArgs('GET', $url)
                                ->andThrow(new \Exception('Error'));
                        })
                    );
            })
        );

        $request = Request::factory()
            ->create([
                'url' => $url,
                'requestOptions' => [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ],
            ]);

        DefaultCrawling::dispatch($request);

        $request->refresh();
        $this->assertEquals('request-failed', $request->state_code);
    }
}
