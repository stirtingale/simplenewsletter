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
require_once __DIR__ . '/../src/SendyClient.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$htmlCleaner = new HtmlCleaner();
$emailFormatter = new EmailFormatter($htmlCleaner);
$sendyClient = new SendyClient(
    $_ENV['SENDY_API_KEY'],
    $_ENV['SENDY_BRAND_ID'],
    $_ENV['SENDY_URL'],
    $_ENV['SENDY_FROM_NAME'],
    $_ENV['SENDY_FROM_EMAIL'],
    $_ENV['SENDY_REPLY_TO']
);

$preview = '';
$sendyUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['content'])) {
        if (isset($_POST['action']) && $_POST['action'] === 'send') {
            try {
                $formattedContent = $emailFormatter->formatContent($_POST['content'], $_POST['subject']);
                $sendyUrl = $sendyClient->createCampaign($_POST['subject'], $formattedContent, $emailFormatter);
                $preview = $emailFormatter->formatContent($_POST['content'], $_POST['subject'] ?? '');
                $success = $sendyUrl;
                // header("Location: " . $sendyUrl);
                // exit;
            } catch (Exception $e) {
                $preview = $emailFormatter->formatContent($_POST['content'], $_POST['subject']);
                $sendyResponse = "Error: " . $e->getMessage();
            }
        } else {
            $preview = $emailFormatter->formatContent($_POST['content'], $_POST['subject'] ?? '');
        }
    }
}

$tinymceConfig = require __DIR__ . '/../config/tinymce.php';
require __DIR__ . '/../templates/base.html.php';
