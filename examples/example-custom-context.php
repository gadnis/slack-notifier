<?php

require __DIR__ . '/bootstrap.php';

use Edbox\Slack\ArrayThrottleStorage;
use Edbox\Slack\ContextProviderInterface;
use Edbox\Slack\SlackClient;
use Edbox\Slack\SlackFormatter;
use Edbox\Slack\SlackMessage;
use Edbox\Slack\SlackThrottle;

class CustomContext implements ContextProviderInterface {
    public function getPrefix(): array {
        return ['api-gateway', 'region=eu-west'];
    }
}

$slack = new SlackClient(
    new ExampleWebhookProvider(),
    new ExampleLogger(),
    new SlackFormatter(),
    new SlackThrottle(60, new ArrayThrottleStorage()),
    new CustomContext()
);

$slack->send(SlackMessage::info("Custom context example"));
