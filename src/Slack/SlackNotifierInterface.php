<?php

declare(strict_types=1);

namespace Edbox\Slack;

interface SlackNotifierInterface
{
    public function info(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
    public function notify(string $level, string $message, array $context = []): void;
}
