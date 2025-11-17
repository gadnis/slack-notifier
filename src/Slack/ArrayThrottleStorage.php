<?php
namespace Edbox\Slack;

class ArrayThrottleStorage implements ThrottleStorageInterface
{
    private array $data = [];

    public function get(string $key): ?int {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, int $timestamp): void {
        $this->data[$key] = $timestamp;
    }
}
