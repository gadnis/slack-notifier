<?php

require __DIR__ . '/bootstrap.php';

use Edbox\Slack\SlackMessage;

$slack = build_slack_client();
$slack->send(SlackMessage::warning("Something might be wrong soon."));
