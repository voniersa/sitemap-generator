<?php

declare(strict_types=1);

namespace voniersa\sitemap\generator;

use DomDocument;

class HTMLLinkCrawler
{
    public function crawlHtmlForLinks(string $html, string $baseUrl): array
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $links = [];
        $baseHost = parse_url($baseUrl, PHP_URL_HOST);

        $excludeExtensions = [
            'jpg','jpeg','png','gif','bmp','svg','webp','ico',
            'pdf','doc','docx','xls','xlsx','ppt','pptx',
            'zip','rar','tar','gz','7z','mp3','mp4','mov','avi','wmv','flv','wav','ogg',
        ];

        foreach ($dom->getElementsByTagName('a') as $aTag) {
            $href = trim($aTag->getAttribute('href'));

            if (
                empty($href)
                || preg_match('/^(mailto:|tel:|javascript:|#|ftp:|irc:|sms:)/i', $href)
            ) {
                continue;
            }

            if (!preg_match('#^https?://#i', $href)) {
                $href = rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
            }

            $linkHost = parse_url($href, PHP_URL_HOST);

            if ($linkHost === $baseHost) {
                $href = strtok($href, '#');

                $path = parse_url($href, PHP_URL_PATH);
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($extension && in_array($extension, $excludeExtensions, true)) {
                    continue;
                }
                $links[] = $href;
            }
        }
        return array_unique($links);
    }
}
