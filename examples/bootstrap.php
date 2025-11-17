<?php

// Use Composer autoload if installed
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    // Fallback to PSR-4 manual loading
    spl_autoload_register(function ($class) {
        $prefix = "Edbox\\Slack\\";
        $baseDir = __DIR__ . '/../src/Slack/';

        if (strpos($class, $prefix) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace("\\", "/", $relative) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        }
    });
}

use Edbox\Slack\ArrayThrottleStorage;
use Edbox\Slack\DefaultContextProvider;
use Edbox\Slack\SlackClient;
use Edbox\Slack\SlackFormatter;
use Edbox\Slack\SlackThrottle;

// Replace this with your real webhook
class ExampleWebhookProvider implements \Edbox\Slack\WebhookProviderInterface {
    public function getWebhookUrl(): ?string {
        return 'https://hooks.slack.com/services/XXXXX/YYYYY/ZZZZZ';
    }
}

// Basic logger (echo only)
class ExampleLogger implements \Psr\Log\LoggerInterface {
    public function emergency($msg, array $ctx = []) : void { echo "EMERGENCY: $msg\n"; }
    public function alert($msg, array $ctx = [])     : void { echo "ALERT: $msg\n"; }
    public function critical($msg, array $ctx = [])  : void { echo "CRITICAL: $msg\n"; }
    public function error($msg, array $ctx = [])     : void { echo "ERROR: $msg\n"; }
    public function warning($msg, array $ctx = [])   : void { echo "WARNING: $msg\n"; }
    public function notice($msg, array $ctx = [])    : void { echo "NOTICE: $msg\n"; }
    public function info($msg, array $ctx = [])      : void { echo "INFO: $msg\n"; }
    public function debug($msg, array $ctx = [])     : void { echo "DEBUG: $msg\n"; }
    public function log($level, $msg, array $ctx = []) : void { echo strtoupper($level) . ": $msg\n"; }
}

function build_slack_client(): SlackClient
{
    return new SlackClient(
        new ExampleWebhookProvider(),
        new ExampleLogger(),
        new SlackFormatter(),
        new SlackThrottle(60, new ArrayThrottleStorage()), // throttle 1 min
        new DefaultContextProvider()
    );
}
