<?php

namespace App\Services\Client;

use \GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;

class Client
{
    private string $contentType = 'application/x-www-form-urlencoded';

    public function __construct(private GuzzleClient $guzzleClient)
    {
    }

    public function withOptions(array $options): self
    {
        $this->guzzleClient = new GuzzleClient($options);

        return $this;
    }

    public function request(string $method, string $url, array $options = []): Response
    {
        $options['headers']['Content-Type'] = $this->contentType;

        return $this->guzzleClient->request($method, $url, $options);
    }
}
