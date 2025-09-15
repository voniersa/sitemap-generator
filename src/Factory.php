<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use GuzzleHttp\Client;

class Factory
{
    public function createProcessor(bool $sslVerify): Processor
    {
        return new Processor(
            new HTTPClient(new Client(['verify' => $sslVerify])),
            new HTMLLinkCrawler(),
            new XMLSitemapGenerator(),
            new FileSystemAdapter()
        );
    }
}
