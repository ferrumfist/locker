<?php

namespace Locker\Resources;

class LockResource
{
    private string $name;
    private ?int $expire = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTTL(?int $ttl): LockResource
    {
        $this->expire = $ttl !== null ? time() + $ttl : null;
        return $this;
    }

    public function setExpire(?int $expire): LockResource
    {
        $this->expire = $expire;
        return $this;
    }

    public function isValid(): bool
    {
        if ($this->expire && $this->expire <= time()) {
            return false;
        }

        return true;
    }
}
