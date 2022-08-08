<?php

namespace Locker\Store;

interface LockStoreInterface
{
    public function set($key, $value): bool;

    public function get($key): ?string;

    public function remove($key): bool;
}
