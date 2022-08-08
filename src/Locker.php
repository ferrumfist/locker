<?php

namespace Locker;

use Locker\Store\LockStoreInterface;
use Locker\Resources\LockResource;

class Locker
{
    private LockStoreInterface $store;

    /**
     * @param \Locker\Store\LockStoreInterface $store
     */
    public function __construct(LockStoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Locking resource
     *
     * @param \Locker\Resources\LockResource $resource
     *
     * @return bool
     */
    public function lock(LockResource $resource): bool
    {
        if ($this->isLocked($resource)) {
            return false;
        }

        return $this->store->set(
            $this->getKey($resource->getName()),
            serialize($resource)
        );
    }

    /**
     * Checking if a resource is locked
     *
     * @param \Locker\Resources\LockResource $resource
     *
     * @return bool
     */
    public function isLocked(LockResource $resource): bool
    {
        $data = $this->store->get($this->getKey($resource->getName()));

        if ($data) {
            /**
             * @var LockResource $storeResource
            */
            $storeResource = @unserialize($data);
            return $storeResource instanceof LockResource
                && $storeResource->isValid();
        }

        return false;
    }

    /**
     * Resource unlocking
     *
     * @param \Locker\Resources\LockResource $resource
     *
     * @return bool
     */
    public function unlock(LockResource $resource): bool
    {
        return $this->store->remove($this->getKey($resource->getName()));
    }

    /**
     * Getting the name of the key to work with the repository
     *
     * @param $name
     *
     * @return string
     */
    protected function getKey($name): string
    {
        return "locker_$name";
    }
}
