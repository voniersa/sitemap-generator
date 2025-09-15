<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(XMLSitemapGenerator::class)]
class XMLSitemapGeneratorTest extends TestCase
{
    public function testCanGenerateXMLSitemap(): void
    {
        $expected = file_get_contents(__DIR__ . '/../data/xmlSitemapTestTemplate.xml');

        $generator = new XMLSitemapGenerator();

        $this->assertEquals(
            $expected,
            $generator->generateXMLSitemap(
                ['https://localhost/', 'https://localhost/page1', 'https://localhost/page2'],
                ChangeFrequency::MONTHLY
            )
        );
    }
}
