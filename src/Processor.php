<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

class Processor
{
    public function __construct(
        private readonly HTTPClient $httpClient,
        private readonly HTMLLinkCrawler $htmlLinkCrawler,
        private readonly XMLSitemapGenerator $xmlSitemapGenerator,
        private readonly FileSystemAdapter $fileSystemAdapter,
    ) {
    }

    public function process(string $baseUrl, ChangeFrequency $changeFrequency, string $outputDir): void
    {
        $linksToVisit = [$baseUrl];
        $visitedLinks = [];

        while (!empty($linksToVisit)) {
            foreach ($linksToVisit as $index => $link) {
                if (in_array($link, $visitedLinks)) {
                    unset($linksToVisit[$index]);
                    continue;
                }

                $html = $this->httpClient->request($link);
                $newLinks = $this->htmlLinkCrawler->crawlHtmlForLinks($html, $baseUrl);
                $linksToVisit = array_merge($linksToVisit, $newLinks);
                $linksToVisit = array_unique($linksToVisit);
                unset($linksToVisit[$index]);

                $visitedLinks[] = $link;
                $visitedLinks = array_unique($visitedLinks);
            }
        }

        $xml = $this->xmlSitemapGenerator->generateXMLSitemap($visitedLinks, $changeFrequency);
        $this->fileSystemAdapter->saveXMLStringToFile($outputDir, $xml);
    }
}
