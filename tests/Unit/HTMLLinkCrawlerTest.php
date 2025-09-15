<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HtmlLinkCrawler::class)]
class HTMLLinkCrawlerTest extends TestCase
{
    public function testCrawlHtmlForLinks(): void
    {
        $expected = ['https://localhost/test1', 'https://localhost/test2'];

        $crawler = new HtmlLinkCrawler();
        $actual = $crawler->crawlHtmlForLinks(
            file_get_contents(__DIR__ . '/../data/htmlTestTemplate.html'),
            'https://localhost/'
        );

        $this->assertSame($expected, $actual);
    }
}
