<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Factory::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(Processor::class)]
class FactoryTest extends TestCase
{
    public function testCanCreateProcessor(): void
    {
        $factory = new Factory();
        $this->assertInstanceOf(Processor::class, $factory->createProcessor(false));
    }
}
