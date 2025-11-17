<?php
namespace Edbox\Slack;

class SlackFormatter
{
    private array $emoji = [
        'info' => 'ℹ️',
        'warning' => '⚠️',
        'error' => '🔥',
    ];

    public function format(SlackMessage $msg, array $prefix = []): string
    {
        $level = $msg->getLevel();
        $emoji = $this->emoji[$level] ?? '🔔';

        $pre = '';
        if (!empty($prefix)) {
            $pre = implode(' | ', $prefix) . " | ";
        }

        $full = $pre . $msg->getMessage();

        if ($ctx = $msg->getContext()) {
            $full .= "\nContext: " . json_encode($ctx, JSON_PRETTY_PRINT);
        }

        return sprintf("%s *[%s]*\n```%s```", $emoji, strtoupper($level), $full);
    }
}
