<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use GuzzleHttp\Client;

class HTTPClient
{
    public function __construct(private readonly Client $client)
    {
    }

    public function request(string $url): string
    {
        $response = $this->client->get($url);
        usleep(200000);
        return $response->getBody()->getContents();
    }
}
