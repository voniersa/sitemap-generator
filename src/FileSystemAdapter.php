<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

/**
 * @codeCoverageIgnore
 */
class FileSystemAdapter
{
    public function saveXMLStringToFile(string $outputDir, string $xmlString): void
    {
        file_put_contents($outputDir . '/sitemap.xml', $xmlString);
    }
}
