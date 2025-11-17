<?php

namespace Edbox\Slack;

use Edbox\Slack\ThrottleStorageInterface;

/**
 * File-based throttle storage.
 *
 * Suitable for frameworks, CMS, standalone PHP or any environment.
 * Persists timestamps in flat files: storage_dir/md5(key).throttle
 */
class FileThrottleStorage implements ThrottleStorageInterface
{
    private string $dir;

    public function __construct(string $directory)
    {
        $this->dir = rtrim($directory, '/').'/';

        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    private function resolvePath(string $key): string
    {
        return $this->dir . md5($key) . '.throttle';
    }

    public function get(string $key): ?int
    {
        $file = $this->resolvePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $contents = trim(file_get_contents($file));

        return $contents === '' ? null : (int)$contents;
    }

    public function set(string $key, int $timestamp): void
    {
        file_put_contents($this->resolvePath($key), (string)$timestamp);
    }

    public function delete(string $key): void
    {
        $file = $this->resolvePath($key);

        if (file_exists($file)) {
            unlink($file);
        }
    }
}
