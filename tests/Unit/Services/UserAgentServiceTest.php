<?php

namespace Tests\Unit\Services;

use App\Services\UserAgent\UserAgentService;
use Tests\TestCase;

class UserAgentServiceTest extends TestCase
{
    /**
     * @dataProvider dataProviderFiltering
     * @return void
     */
    public function testGetUserAgentWithFilter(string $osType, string $agentName)
    {
        $userAgent = app(UserAgentService::class);
        $userAgent = $userAgent->random([
            'device_type' => 'Desktop',
            'os_type' => $osType,
            'agent_name' => $agentName,
        ]);

        $this->assertIsArray($userAgent);
        $this->assertEquals('Desktop', $userAgent['device_type']);
        $this->assertEquals($osType, $userAgent['os_type']);
        $this->assertEquals($agentName, $userAgent['agent_name']);
    }

    public static function dataProviderFiltering(): array
    {
        return [
            [
                'OS X',
                'Safari',
            ],
            [
                'Windows',
                'Chrome',
            ],
            [
                'Windows',
                'Firefox',
            ],
        ];
    }

    public function testGetUserAgentWithFilterEmpty()
    {
        $this->expectException(\Exception::class);

        $userAgent = app(UserAgentService::class);
        $userAgent->random([
            'device_type' => 'Desktop',
            'os_type' => $this->faker->text
        ]);
    }
}
