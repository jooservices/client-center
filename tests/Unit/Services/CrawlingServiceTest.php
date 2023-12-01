<?php

namespace Tests\Unit\Services;

use App\Models\Request;
use App\Services\CrawlingService;
use Tests\TestCase;

class CrawlingServiceTest extends TestCase
{
    public function testGetDefaultCrawlingRequests() {
        $service = app(CrawlingService::class);

        Request::factory()->count(10)->create();
        Request::factory()->count(11)->create([
            'state_code' => 'processing'
        ]);

        $this->assertEquals(
            10, $service->getDefaultCrawlingRequests()->count()
        );
    }
}
