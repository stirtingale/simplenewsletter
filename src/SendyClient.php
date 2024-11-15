<?php

class SendyClient
{
    private $apiKey;
    private $brandId;
    private $baseUrl;
    private $fromName;
    private $fromEmail;
    private $replyTo;

    public function __construct($apiKey, $brandId, $baseUrl, $fromName, $fromEmail, $replyTo)
    {
        $this->apiKey = $apiKey;
        $this->brandId = $brandId;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->replyTo = $replyTo;
    }

    public function createCampaign($subject, $content)
    {
        error_log("Creating campaign with URL: {$this->baseUrl}/api/campaigns/create.php");

        $plaintext = new \Html2Text\Html2Text($content);

        $postdata = http_build_query([
            'api_key' => $this->apiKey,
            'brand_id' => $this->brandId,
            'send_campaign' => '0',
            'html_text' => $content,
            'plain_text' => $plaintext->getText(),
            'from_name' => $this->fromName,
            'from_email' => $this->fromEmail,
            'reply_to' => $this->replyTo,
            'title' => $subject,
            'subject' => $subject,
        ]);

        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
                'ignore_errors' => true // This will prevent 404s from causing a fatal error
            ]
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents($this->baseUrl . '/api/campaigns/create.php', false, $context);
        if ($result === false) {
            $error = error_get_last();
            error_log("Sendy API Error: " . print_r($error, true));
            throw new Exception('Failed to create campaign: ' . ($error['message'] ?? 'Unknown error'));
        }

        error_log("Sendy API Response: " . $result);
        return $result;
    }
}
