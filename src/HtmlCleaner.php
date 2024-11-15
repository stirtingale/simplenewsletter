<?php

class HtmlCleaner
{

    public function clean(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        $this->removeGoogleDocsAttributes($xpath);
        $this->cleanupStyles($xpath);
        $this->formatLists($xpath);
        $this->formatLinks($xpath);
        $this->formatHeaders($xpath);
        $this->formatListItems($xpath);
        $this->formatParagraphs($xpath);
    }

    private function removeGoogleDocsAttributes(DOMXPath $xpath)
    {
        $elements = $xpath->query('//*[@class]|//*[@id]');
        foreach ($elements as $element) {
            $element->removeAttribute('class');
            $element->removeAttribute('id');
        }
    }

    private function cleanupStyles(DOMXPath $xpath)
    {
        $elements = $xpath->query('//*[@style]');
        foreach ($elements as $element) {
            $style = $element->getAttribute('style');

            $style = $this->standardizeFonts($style);
            $style = $this->standardizeLineHeight($style);
            $style = $this->standardizeFontSizes($style);
            $style = $this->convertColors($style);

            $element->setAttribute('style', $style);
        }
    }

    private function standardizeFonts($style)
    {
        return preg_replace('/font-family:[^;]+;?/', '"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', $style);
    }

    private function standardizeLineHeight($style)
    {
        return preg_replace('/line-height:[^;]+;?/', 'line-height: 1.6;', $style);
    }

    private function standardizeFontSizes($style)
    {
        if (preg_match('/font-size:\s*(\d+)(pt|px)/', $style, $matches)) {
            $size = intval($matches[1]);
            $unit = $matches[2];

            if ($unit === 'pt') {
                $size = ceil($size * 1.333);
            }

            $newSize = '14px';
            if ($size >= 24) $newSize = '24px';
            else if ($size >= 20) $newSize = '20px';
            else if ($size >= 16) $newSize = '16px';

            return preg_replace('/font-size:\s*\d+(pt|px)/', "font-size: $newSize", $style);
        }
        return $style;
    }

    private function convertColors($style)
    {
        return preg_replace_callback('/rgb\((\d+),\s*(\d+),\s*(\d+)\)/', function ($matches) {
            return sprintf('#%02x%02x%02x', $matches[1], $matches[2], $matches[3]);
        }, $style);
    }

    private function formatLists(DOMXPath $xpath)
    {
        foreach (['ul', 'ol'] as $listType) {
            $lists = $xpath->query("//$listType");
            foreach ($lists as $list) {
                $list->setAttribute('style', 'margin: 10px 0; padding-left: 20px;');
            }
        }
    }

    private function formatParagraphs(DOMXPath $xpath)
    {
        $paragraphs = $xpath->query("//p");
        foreach ($paragraphs as $p) {
            $style = $p->getAttribute('style');
            $style .= 'margin: 0 0 1em 0;';
            $p->setAttribute('style', $style);
        }
    }

    private function formatLinks(DOMXPath $xpath)
    {
        $links = $xpath->query("//a");
        foreach ($links as $link) {
            $style = $link->getAttribute('style');
            $style .= 'color: #ff5538; text-decoration: none;';
            $link->setAttribute('style', $style);
        }
    }

    private function formatHeaders(DOMXPath $xpath)
    {
        $headerStyles = [
            'h1' => 'font-size: 32px; font-weight: normal; text-transform:uppercase; margin: 20px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;',
            'h2' => 'font-size: 28px; font-weight: normal; text-transform:uppercase; margin: 18px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;',
            'h3' => 'font-size: 24px; font-weight: normal; text-transform:uppercase; margin: 16px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;',
            'h4' => 'font-size: 20px; font-weight: normal; text-transform:uppercase; margin: 14px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;',
            'h5' => 'font-size: 16px; font-weight: normal; text-transform:uppercase; margin: 12px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;',
            'h6' => 'font-size: 14px; font-weight: normal; text-transform:uppercase; margin: 10px 0; font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;'
        ];

        foreach ($headerStyles as $tag => $style) {
            $headers = $xpath->query("//$tag");
            foreach ($headers as $header) {
                $header->setAttribute('style', $style);
            }
        }
    }

    private function formatListItems(DOMXPath $xpath)
    {
        $listItems = $xpath->query("//li");
        foreach ($listItems as $li) {
            $style = $li->getAttribute('style');
            $style .= 'margin-bottom: 5px; white-space: normal;';
            $li->setAttribute('style', $style);
        }
    }
}
