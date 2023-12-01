<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class CrawlingControllerTest extends TestCase
{
    public function testRegisterCrawling()
    {
        $response = $this->postJson('/api/crawling', [
            'url' => 'https://www.google.com'
        ])->assertStatus(201)
            ->assertJsonStructure([
                'uuid',
                'url',
                'requestOptions',
                'options',
                'state_code',
            ])->getContent();

        $response = json_decode($response, true);

        $this->assertEquals('init', $response['state_code']);
        $this->assertArrayHasKey(
            'User-Agent',
            $response['requestOptions']['headers']
        );
    }
}
