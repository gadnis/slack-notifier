<?php
namespace Edbox\Slack;

interface ThrottleStorageInterface {
    public function get(string $key): ?int;
    public function set(string $key, int $timestamp): void;
}
