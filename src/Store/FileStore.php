<?php

namespace Locker\Store;

class FileStore implements LockStoreInterface
{
    private string $pathToDir;

    /**
     * @throws \Exception
     */
    public function __construct(string $pathToDir)
    {
        $this->pathToDir = $pathToDir;
        if (!file_exists($this->pathToDir)) {
            mkdir($this->pathToDir, 0777, true);
        }
    }

    private function getFilePath($key): string
    {
        return $this->pathToDir . '/' . $key . '.txt';
    }

    public function set($key, $value): bool
    {
        return (bool)file_put_contents($this->getFilePath($key), $value);
    }

    public function get($key): ?string
    {
        return @file_get_contents($this->getFilePath($key));
    }

    public function remove($key): bool
    {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            return unlink($file);
        }

        return true;
    }
}
