<?php

namespace Tests\Unit\Services;

use App\Models\Client;
use App\Models\Request;
use App\Services\CrawlingService;
use Tests\TestCase;

class CrawlingServiceTest extends TestCase
{
    public function testGetDefaultCrawlingRequests()
    {
        $service = app(CrawlingService::class);

        Client::factory()->create([
            'queues' => [
                [
                    'name' => 'default',
                    'workers' => 2
                ]
            ]
        ]);
        Client::factory()->create([
            'queues' => [
                [
                    'name' => 'default',
                    'workers' => 3
                ]
            ]
        ]);
        Client::factory()->create([
            'queues' => [
                [
                    'name' => 'another',
                    'workers' => 3
                ]
            ]
        ]);

        Request::factory()->count(4)->create([
            'service' => 'default',
        ]);
        Request::factory()->count(2)->create([
            'service' => 'another',
        ]);
        Request::factory()->count(2)->create([
            'service' => 'another2',
        ]);

        Request::factory()->count(11)->create([
            'state_code' => 'processing'
        ]);

        $this->assertEquals(2, $service->getDefaultCrawlingRequests()->count());
        $this->assertArrayHasKey('default', $service->getDefaultCrawlingRequests()->toArray());
        $this->assertArrayHasKey('another', $service->getDefaultCrawlingRequests()->toArray());
        $this->assertArrayNotHasKey('another2', $service->getDefaultCrawlingRequests()->toArray());
        $this->assertEquals(4, $service->getDefaultCrawlingRequests()['default']->count());
        $this->assertEquals(2, $service->getDefaultCrawlingRequests()['another']->count());
    }
}
