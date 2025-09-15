<?php

declare(strict_types=1);

use voniersa\sitemap\generator\ChangeFrequency;
use voniersa\sitemap\generator\Factory;

require "./vendor/autoload.php";

$options = [];
for ($i = 1; $i < count($argv); $i += 2) {
    if (isset($argv[$i + 1])) {
        switch ($argv[$i]) {
            case '--base-url':
                $options['base-url'] = $argv[$i + 1];
                break;
            case '--change-frequency':
                $options['change-frequency'] = $argv[$i + 1];
                break;
            case '--ssl-verify':
                $options['ssl-verify'] = (string) $argv[$i + 1];
                break;
            case '--output-dir':
                $options['output-dir'] = (string) $argv[$i + 1];
                break;
        }
    }
}

$baseUrl = $options['base-url'] ?? 'https://localhost/';
$changeFrequency = isset($options['change-frequency']) ?
    ChangeFrequency::from($options['change-frequency']) : ChangeFrequency::MONTHLY;
$sslVerify = isset($options['ssl-verify']) && $options['ssl-verify'] === 'true';
$outputDir = $options['output-dir'] ?? './';

$factory = new Factory();
$processor = $factory->createProcessor($sslVerify);

$processor->process($baseUrl, $changeFrequency, $outputDir);
