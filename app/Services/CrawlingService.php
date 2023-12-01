<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Request;
use App\Services\UserAgent\UserAgentService;
use Illuminate\Support\Collection;

class CrawlingService
{
    public function store(array $data): Request
    {
        $requestOptions = $data['requestOptions'] ?? [];
        $requestOptions['headers'] = array_merge(
            $requestOptions['headers'] ?? [],
            [
                'User-Agent' => app(UserAgentService::class)->random([
                    'device_type' => 'Desktop',
                ])['agent_string']
            ]);

        $data['requestOptions'] = $requestOptions;
        $data['options'] = $data['options'] ?? [
            'max_retries' => 3,
            'retry_delay' => 1000,
        ];

        $data = array_merge(
            $data,
            [
                'state_code' => 'init',
            ]
        );

        return Request::create($data);
    }

    public function getDefaultCrawlingRequests(): Collection
    {
        return Request::where([
            'state_code' => 'init',
        ])->whereNull('client_uuid')->limit(Client::count() * 2)->get();
    }
}
