<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use DOMDocument;

class XMLSitemapGenerator
{
    public function generateXMLSitemap(array $urls, ChangeFrequency $changeFrequency): string
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $urlset = $xml->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $index => $url) {
            $urlElem = $xml->createElement('url');
            $loc = $xml->createElement('loc', htmlspecialchars($url));
            $urlElem->appendChild($loc);
            $urlElem->appendChild($xml->createElement('lastmod', date('Y-m-d')));
            $urlElem->appendChild($xml->createElement('changefreq', $changeFrequency->value));

            if ($index === 0) {
                $urlElem->appendChild($xml->createElement('priority', '1.0'));
            } else {
                $urlElem->appendChild($xml->createElement('priority', '0.5'));
            }

            $urlset->appendChild($urlElem);
        }

        $xml->appendChild($urlset);
        return $xml->saveXML();
    }
}
