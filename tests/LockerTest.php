<?php

use PHPUnit\Framework\TestCase;
use Locker\Locker;
use Locker\Store\LockStoreInterface;
use Locker\Resources\LockResource;

class LockerTest extends TestCase
{






    public function testIsLockedBrokenData()
    {
        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('get')
            ->willReturn('broken');

        $resource = $this->createMock(LockResource::class);

        $locker = new Locker($store);
        $this->assertFalse($locker->isLocked($resource));
    }
}
