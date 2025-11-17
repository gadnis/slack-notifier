<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Edbox\Slack\DefaultContextProvider;
use Edbox\Slack\SlackClient;
use Edbox\Slack\SlackFormatter;
use Edbox\Slack\SlackThrottle;
use Edbox\Slack\WebhookProviderInterface;
use Psr\Log\LoggerInterface;

/**
 * Simple demo implementation of WebhookProviderInterface.
 */
class ExampleWebhookProvider implements WebhookProviderInterface
{
    private string $url;
    public function __construct(string $url) { $this->url = $url; }
    public function getWebhookUrl(): ?string { return $this->url; }
}

/**
 * Simple stdout logger for examples.
 */
class ExampleLogger implements LoggerInterface
{
    public function emergency($msg, array $ctx = [])  : void { echo "EMERGENCY: $msg\n"; }
    public function alert($msg, array $ctx = [])      : void { echo "ALERT: $msg\n"; }
    public function critical($msg, array $ctx = [])   : void { echo "CRITICAL: $msg\n"; }
    public function error($msg, array $ctx = [])      : void { echo "ERROR: $msg\n"; }
    public function warning($msg, array $ctx = [])    : void { echo "WARNING: $msg\n"; }
    public function notice($msg, array $ctx = [])     : void { echo "NOTICE: $msg\n"; }
    public function info($msg, array $ctx = [])       : void { echo "INFO: $msg\n"; }
    public function debug($msg, array $ctx = [])      : void { echo "DEBUG: $msg\n"; }
    public function log($level, $msg, array $ctx = []): void {
        echo strtoupper($level) . ": $msg\n";
    }
}

$webhook = new ExampleWebhookProvider('https://hooks.slack.com/services/.../../...');
$logger  = new ExampleLogger();

$formatter = new SlackFormatter();
$throttle  = new SlackThrottle(5, null); // 5 seconds throttle for testing
$context   = new DefaultContextProvider();

// Create SlackClient with full dependencies
return new SlackClient($webhook, $logger, $formatter, $throttle, $context);
