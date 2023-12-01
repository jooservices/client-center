<?php

namespace Tests\Unit\Services;

use App\Services\UserAgent\UserAgentService;
use Tests\TestCase;

class UserAgentServiceTest extends TestCase
{
    public function testGetUserAgentWithFilter()
    {
        $userAgent = app(UserAgentService::class);
        $userAgent = $userAgent->random([
            'device_type' => 'Desktop',
            'os_name' => 'OS X',
            'agent_name' => 'Safari',
        ]);

       dd($userAgent);

    }
}
