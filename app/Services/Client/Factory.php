<?php

namespace App\Services\Client;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Factory
{
    protected HandlerStack $handler;
    protected MockHandler $mocking;

    private Client $client;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): self
    {
        $this->handler = HandlerStack::create($this->mocking ?? null);

        return $this;
    }

    public function enableMocking(): self
    {
        $this->mocking = app(MockHandler::class);
        $this->reset();

        return $this;
    }

    public function addMockResponse(
        int    $status = 200,
        array  $headers = [],
        mixed  $body = null,
        string $version = '1.1',
        string $reason = null
    ): self
    {
        if (!isset($this->mocking)) {
            throw new Exception('Mocking is not enabled');
        }

        /*
         * @phpstan-ignore-next-line
         */
        $this->mocking->append(new Response($status, $headers, $body, $version, $reason));

        return $this;
    }

    public function addMockRequestException(
        string            $message,
        RequestInterface  $request,
        ResponseInterface $response = null,
        Throwable         $previous = null
    ): self
    {
        if (!isset($this->mocking)) {
            throw new Exception('Mocking is not enabled');
        }

        $this->mocking->append(new RequestException($message, $request, $response, $previous));

        return $this;
    }

    public function enableRetries(int $maxRetries = 3, int $delayInSec = 1, int $minErrorCode = 500): self
    {
        $decider = function ($retries, $_, $response) use ($maxRetries, $minErrorCode) {
            return $retries < $maxRetries
                && $response instanceof ResponseInterface
                && $response->getStatusCode() >= $minErrorCode;
        };

        $increasingDelay = fn($attempt) => $attempt * $delayInSec * 1000;

        return $this->withMiddleware(Middleware::retry($decider, $increasingDelay), 'retry');
    }

    public function withMiddleware(callable $middleware, string $name = ''): self
    {
        $this->handler->push($middleware, $name);

        return $this;
    }

    public function make(array $options = []): Client
    {
        $guzzleClient = new GuzzleClient(array_merge(['handler' => $this->handler], $options));
        $this->client = new Client($guzzleClient);

        $this->reset();

        return $this->client;
    }
}
