<?php

require __DIR__ . '/bootstrap.php';

use Edbox\Slack\SlackMessage;

$slack = build_slack_client();
$slack->send(SlackMessage::info("This is an example info message."));
