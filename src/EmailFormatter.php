<?php

class EmailFormatter
{
    private $htmlCleaner;
    private $title;

    public function __construct(HtmlCleaner $htmlCleaner)
    {
        $this->htmlCleaner = $htmlCleaner;
    }

    public function formatContent($content, $title = '')
    {
        $this->title = $title;

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();

        $this->htmlCleaner->clean($dom);

        return $this->wrapInTemplate($dom->saveHTML());
    }

    private function getRandomEmoji()
    {
        $emojis = [
            "🌟",
            "✨",
            "📨",
            "📬",
            "📮",
            "💌",
            "📝",
            "✏️",
            "📰",
            "🗞️",
            "🎯",
            "🎨",
            "🌈",
            "🍀",
            "⭐",
            "🎭",
            "🎪",
            "🎡",
            "🎢",
            "🎠",
            "🎪",
            "🎨"
        ];
        return $emojis[array_rand($emojis)];
    }

    private function getTitle()
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        $date = date('jS F');
        return str_replace(
            ['{date}', '{emoji}'],
            [$date, $this->getRandomEmoji()],
            $_ENV['SENDY_TITLE']
        );
    }

    private function wrapInTemplate($content)
    {
        $title = $this->getTitle();

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$title}</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #ffffff;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333333;">
                <img src="{$_ENV['SENDY_LOGO_URL']}" alt="Logo" style="width: 3.6rem; height: auto; display: block; margin-bottom: 62px;">
                <h1 style="margin:64px 0 32px; color: #ff5538; text-align:center; font-size: 28px; font-weight: 600; letter-spacing:0.02em; text-transform:none; margin: 14px 0; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;">{$title}</h1>
                {$content}
            </div>
        </body>
        </html>
        HTML;
    }
}
