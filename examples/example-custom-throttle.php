<?php

require __DIR__ . '/bootstrap.php';

use Edbox\Slack\DefaultContextProvider;
use Edbox\Slack\SlackClient;
use Edbox\Slack\SlackFormatter;
use Edbox\Slack\SlackMessage;
use Edbox\Slack\SlackThrottle;
use Edbox\Slack\ThrottleStorageInterface;

// Example: storing throttle keys in a simple file
class FileThrottleStorage implements ThrottleStorageInterface {
    private string $file;

    public function __construct(string $file) {
        $this->file = $file;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
    }

    public function get(string $key): ?int {
        $data = json_decode(file_get_contents($this->file), true);
        return $data[$key] ?? null;
    }

    public function set(string $key, int $timestamp): void {
        $data = json_decode(file_get_contents($this->file), true);
        $data[$key] = $timestamp;
        file_put_contents($this->file, json_encode($data));
    }
}

$storage = new FileThrottleStorage(__DIR__ . '/throttle.json');

$slack = new SlackClient(
    new ExampleWebhookProvider(),
    new ExampleLogger(),
    new SlackFormatter(),
    new SlackThrottle(120, $storage), // throttle 120s
    new DefaultContextProvider()
);

$slack->send(SlackMessage::warning("Custom throttle storage example"));
