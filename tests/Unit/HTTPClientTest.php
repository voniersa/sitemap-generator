<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(HttpClient::class)]
class HTTPClientTest extends TestCase
{
    public function testCanRequest(): void
    {
        $expected = "test response";

        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock->expects($this->once())
            ->method('getContents')
            ->willReturn($expected);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('get')
            ->willReturn($responseMock);

        $httpClient = new HttpClient($clientMock);

        $this->assertSame($expected, $httpClient->request('https://localhost/'));
    }
}
