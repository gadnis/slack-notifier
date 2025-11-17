<?php

namespace Edbox\Slack;

use Edbox\Slack\ThrottleStorageInterface;

/**
 * File-based throttle storage using day-based folder structure.
 *
 * Structure:
 *   /baseDir/YYYY-MM-DD/<hash>.throttle
 *
 * Only today's directory is kept.
 * All older day folders are automatically deleted.
 */
class FileThrottleStorage implements ThrottleStorageInterface
{
    private string $baseDir;

    /**
     * @param string $directory Base directory where daily subfolders will be created.
     */
    public function __construct(string $directory)
    {
        $this->baseDir = rtrim($directory, '/') . '/';

        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir, 0777, true);
        }
    }

    /**
     * Returns today’s folder path (YYYY-MM-DD).
     */
    private function getTodayDir(): string
    {
        return $this->baseDir . date('Y-m-d') . '/';
    }

    /**
     * Resolve full file path for a given throttle key.
     */
    private function resolvePath(string $key): string
    {
        $dayDir = $this->getTodayDir();

        if (!is_dir($dayDir)) {
            mkdir($dayDir, 0777, true);
        }

        return $dayDir . md5($key) . '.throttle';
    }

    /**
     * Read stored throttle timestamp.
     */
    public function get(string $key): ?int
    {
        $file = $this->resolvePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $contents = trim((string)file_get_contents($file));

        return $contents === '' ? null : (int)$contents;
    }

    /**
     * Save timestamp and clean older day folders.
     */
    public function set(string $key, int $timestamp): void
    {
        // Remove all previous day folders
        $this->cleanupOldDays();

        file_put_contents($this->resolvePath($key), (string)$timestamp);
    }

    /**
     * Delete a specific throttle entry.
     */
    public function delete(string $key): void
    {
        $file = $this->resolvePath($key);

        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Delete all date folders except today's.
     *
     * Example:
     *   Keep:  2025-02-07/
     *   Delete: 2025-02-06/, 2025-02-05/, ...
     */
    private function cleanupOldDays(): void
    {
        $today = date('Y-m-d');

        foreach (glob($this->baseDir . '*', GLOB_ONLYDIR) as $folder) {
            $folderName = basename($folder);

            // keep only today
            if ($folderName === $today) {
                continue;
            }

            $this->deleteDirectory($folder);
        }
    }

    /**
     * Recursively delete a directory and its contents.
     */
    private function deleteDirectory(string $dir): void
    {
        foreach (glob($dir . '/*') as $file) {
            @unlink($file);
        }
        @rmdir($dir);
    }
}
