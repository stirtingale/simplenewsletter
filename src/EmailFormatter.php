<?php

class EmailFormatter
{
    private $htmlCleaner;

    public function __construct(HtmlCleaner $htmlCleaner)
    {
        $this->htmlCleaner = $htmlCleaner;
    }

    public function formatContent($content)
    {
        $dom = new DOMDocument();
        $dom->loadHTML(
            mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $this->htmlCleaner->clean($dom);

        return $this->wrapInTemplate($dom->saveHTML());
    }

    private function wrapInTemplate($content)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Template</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #ffffff;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333333;">
                $content
            </div>
        </body>
        </html>
        HTML;
    }
}
