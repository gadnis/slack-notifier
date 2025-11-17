<?php

require __DIR__ . '/bootstrap.php';

use Edbox\Slack\SlackMessage;

$slack = build_slack_client();

$msg = SlackMessage::error("Database connection failed")
    ->withContext([
        'host' => 'db01',
        'retry' => true,
        'time' => date('Y-m-d H:i:s')
    ]);

$slack->send($msg);
