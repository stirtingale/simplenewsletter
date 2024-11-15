<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Add error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/EmailFormatter.php';
require_once __DIR__ . '/../src/HtmlCleaner.php';

$htmlCleaner = new HtmlCleaner();
$emailFormatter = new EmailFormatter($htmlCleaner);
$tinymceConfig = require __DIR__ . '/../config/tinymce.php';

$preview = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $preview = $emailFormatter->formatContent($_POST['content']);
}

// Pass tinymceConfig directly to template
require __DIR__ . '/../templates/base.html.php';
