<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Processor::class)]
class ProcessorTest extends TestCase
{
    public function testCanProcess(): void
    {
        $expected = file_get_contents(__DIR__ . '/../data/xmlSitemapTestTemplate.xml');

        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock->expects($this->exactly(3))->method("request")
            ->willReturn(file_get_contents(__DIR__ . '/../data/htmlTestTemplate.html'));

        $htmlLinkCrawlerMock = $this->createMock(HtmlLinkCrawler::class);
        $htmlLinkCrawlerMock->expects($this->exactly(3))->method('crawlHtmlForLinks')
            ->willReturn([
                'https://localhost/page1',
                'https://localhost/page2',
            ]);

        $xmlSitemapGeneratorMock = $this->createMock(XMLSitemapGenerator::class);
        $xmlSitemapGeneratorMock->expects($this->once())->method('generateXMLSitemap')
            ->willReturn($expected);

        $fileSystemAdapterMock = $this->createMock(FileSystemAdapter::class);
        $fileSystemAdapterMock->expects($this->once())->method('saveXMLStringToFile')
            ->with('output', $expected);

        $processor = new Processor(
            $httpClientMock,
            $htmlLinkCrawlerMock,
            $xmlSitemapGeneratorMock,
            $fileSystemAdapterMock
        );

        $processor->process('https://localhost', ChangeFrequency::MONTHLY, 'output');
    }
}
