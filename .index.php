<?php
require 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

function formatGoogleDocsContent($content)
{
    // Clean up Google Docs specific markup while preserving essential HTML
    $dom = new DOMDocument();
    $dom->loadHTML(
        mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    $xpath = new DOMXPath($dom);

    // Remove Google Docs specific classes and ids
    $elements = $xpath->query('//*[@class]|//*[@id]');
    foreach ($elements as $element) {
        $element->removeAttribute('class');
        $element->removeAttribute('id');
    }

    // Clean up inline styles
    $elements = $xpath->query('//*[@style]');
    foreach ($elements as $element) {
        $style = $element->getAttribute('style');

        // Convert font-family to email-safe fonts
        $style = preg_replace('/font-family:[^;]+;?/', 'font-family: Arial, sans-serif;', $style);

        // Standardize line height
        $style = preg_replace('/line-height:[^;]+;?/', 'line-height: 1.6;', $style);

        // Clean up font sizes
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

            $style = preg_replace('/font-size:\s*\d+(pt|px)/', "font-size: $newSize", $style);
        }

        // Convert RGB colors to hex
        $style = preg_replace_callback('/rgb\((\d+),\s*(\d+),\s*(\d+)\)/', function ($matches) {
            return sprintf('#%02x%02x%02x', $matches[1], $matches[2], $matches[3]);
        }, $style);

        $element->setAttribute('style', $style);
    }

    // Clean up list formatting
    foreach (['ul', 'ol'] as $listType) {
        $lists = $xpath->query("//$listType");
        foreach ($lists as $list) {
            $list->setAttribute('style', 'margin: 10px 0; padding-left: 20px;');
        }
    }

    // Clean up paragraph spacing
    $paragraphs = $xpath->query("//p");
    foreach ($paragraphs as $p) {
        $style = $p->getAttribute('style');
        $style .= 'margin: 0 0 1em 0;';
        $p->setAttribute('style', $style);
    }

    // Wrap in email template
    $template = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Template</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #ffffff;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333333;">
            {$dom->saveHTML()}
        </div>
    </body>
    </html>
    HTML;

    return $template;
}

$preview = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $preview = formatGoogleDocsContent($_POST['content']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Email Content Formatter</title>
    <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .preview-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .buttons {
            margin: 20px 0;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        #formatted-output {
            width: 100%;
            height: 300px;
            margin-top: 20px;
            font-family: monospace;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | code | help',
            height: 400,
            paste_as_text: false,
            paste_enable_default_filters: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,p,br,ul,ol,li,span,a",
            content_style: 'body { font-family: Arial,sans-serif; font-size: 14px; line-height: 1.6; }',
            formats: {
                bold: {
                    inline: 'span',
                    styles: {
                        fontWeight: 'bold'
                    }
                },
                italic: {
                    inline: 'span',
                    styles: {
                        fontStyle: 'italic'
                    }
                }
            },
            setup: function(editor) {
                editor.on('PastePreProcess', function(e) {
                    // Clean up Google Docs specific spans and styles
                    e.content = e.content.replace(/class="[^"]*"/g, '');
                    e.content = e.content.replace(/id="[^"]*"/g, '');
                });
            }
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Email Content Formatter</h1>
        <p>Paste your Google Docs content below and click Format to generate an email template.</p>

        <form method="post" id="formatter">
            <textarea id="editor" name="content"></textarea>
            <div class="buttons">
                <button type="submit">Format for Email</button>
            </div>
        </form>

        <?php if (!empty($preview)): ?>
            <h2>Formatted Result</h2>
            <div class="preview-container">
                <h3>Preview:</h3>
                <?php echo $preview; ?>

                <h3>HTML Code:</h3>
                <textarea id="formatted-output"><?php echo htmlspecialchars($preview); ?></textarea>
            </div>

            <script>
                // Add copy button functionality
                document.addEventListener('DOMContentLoaded', function() {
                    const output = document.getElementById('formatted-output');
                    const copyBtn = document.createElement('button');
                    copyBtn.textContent = 'Copy HTML';
                    copyBtn.onclick = function() {
                        output.select();
                        document.execCommand('copy');
                        alert('HTML copied to clipboard!');
                    };
                    output.parentNode.insertBefore(copyBtn, output);
                });
            </script>
        <?php endif; ?>
    </div>
</body>

</html>