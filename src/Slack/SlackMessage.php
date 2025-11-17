<?php

namespace Edbox\Slack;

/**
 * Represents a structured Slack message with:
 *  - text content
 *  - severity level (info, warning, error)
 *  - optional context key/value metadata
 *
 * Immutable-style design: any modification returns a cloned instance.
 */
class SlackMessage
{
    /** @var string The main message text */
    private string $message;

    /** @var string The severity level (info|warning|error) */
    private string $level;

    /** @var array Context array to enrich the message (key => value) */
    private array $context = [];

    /** @var string[] Allowed message levels */
    private const ALLOWED = ['info', 'warning', 'error'];

    /**
     * SlackMessage constructor.
     *
     * @param string $message The textual message
     * @param string $level   The severity level (default: info)
     */
    public function __construct(string $message, string $level = 'info')
    {
        $this->message = $message;

        // Force valid level; fallback to "error" on invalid input
        $this->level = in_array($level, self::ALLOWED, true)
            ? $level
            : 'error';
    }

    /**
     * Helper: create an INFO level message.
     */
    public static function info(string $msg): self
    {
        return new self($msg, 'info');
    }

    /**
     * Helper: create a WARNING level message.
     */
    public static function warning(string $msg): self
    {
        return new self($msg, 'warning');
    }

    /**
     * Helper: create an ERROR level message.
     */
    public static function error(string $msg): self
    {
        return new self($msg, 'error');
    }

    /**
     * Attach metadata/context to the message.
     *
     * Uses immutable pattern: returns a cloned instance instead of modifying the original one.
     *
     * @param array $ctx Additional context key/values
     * @return self A cloned, enriched SlackMessage instance
     */
    public function withContext(array $ctx): self
    {
        $clone = clone $this;
        $clone->context = array_merge($this->context, $ctx);
        return $clone;
    }

    /**
     * Get the severity level of this message.
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Get the main text message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the associated context metadata.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
