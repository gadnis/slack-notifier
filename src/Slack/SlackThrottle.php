<?php
namespace Edbox\Slack;

class SlackThrottle
{
    private int $seconds;

    /** @var ThrottleStorageInterface|null */
    private ?ThrottleStorageInterface $storage;

    /** In-memory fallback */
    private array $memory = [];

    public function __construct(int $seconds, ?ThrottleStorageInterface $storage = null)
    {
        $this->seconds = $seconds;
        $this->storage = $storage;
    }

    public function shouldBlock(string $key): bool
    {
        $now = time();

        // persistent storage (file-based/db)
        if ($this->storage !== null) {
            $last = $this->storage->get($key);

            if ($last && ($now - $last < $this->seconds)) {
                return true;
            }

            $this->storage->set($key, $now);
            return false;
        }

        // fallback: in-memory (per request)
        $last = $this->memory[$key] ?? null;

        if ($last && ($now - $last < $this->seconds)) {
            return true;
        }

        $this->memory[$key] = $now;
        return false;
    }
}
