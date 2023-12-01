<?php

namespace Tests\Feature\Commands;

use App\Jobs\DefaultCrawling;
use App\Models\Request;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessCrawlingTest extends TestCase
{
    public function testProcessDefaultCrawling()
    {
        Queue::fake();

        Request::factory()->count(5)->create();
        $this->artisan('crawling:process');

        Queue::assertPushed(DefaultCrawling::class);
        $this->assertDatabaseHas('requests', [
            'state_code' => 'processing',
        ], 'mongodb');
    }
}
