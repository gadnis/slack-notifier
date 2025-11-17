<?php
namespace Edbox\Slack;

class SlackThrottle
{
    private int $seconds;
    private ThrottleStorageInterface $storage;

    public function __construct(int $seconds, ThrottleStorageInterface $storage)
    {
        $this->seconds = $seconds;
        $this->storage = $storage;
    }

    public function shouldBlock(string $key): bool
    {
        $last = $this->storage->get($key);
        if ($last && (time() - $last < $this->seconds)) {
            return true;
        }
        $this->storage->set($key, time());
        return false;
    }
}
