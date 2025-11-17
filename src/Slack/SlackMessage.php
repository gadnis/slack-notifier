<?php
namespace Edbox\Slack;

class SlackMessage
{
    private string $message;
    private string $level;
    private array $context = [];

    public function __construct(string $message, string $level='info') {
        $this->message = $message;
        $this->level = $level;
    }

    public static function info(string $msg): self { return new self($msg,'info'); }
    public static function warning(string $msg): self { return new self($msg,'warning'); }
    public static function error(string $msg): self { return new self($msg,'error'); }

    public function withContext(array $ctx): self {
        $this->context = array_merge($this->context, $ctx);
        return $this;
    }

    public function getLevel(): string { return $this->level; }
    public function getMessage(): string { return $this->message; }
    public function getContext(): array { return $this->context; }
}
