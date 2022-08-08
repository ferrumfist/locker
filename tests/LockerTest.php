<?php

use PHPUnit\Framework\TestCase;
use Locker\Locker;
use Locker\Store\LockStoreInterface;
use Locker\Resources\LockResource;

class LockerTest extends TestCase
{
    public function testSuccessLockNoResource()
    {
        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('get')
            ->willReturn(null);
        $store->expects($this->once())
            ->method('set')
            ->willReturn(true);

        $resource = $this->createMock(LockResource::class);

        $locker = new Locker($store);
        $this->assertTrue($locker->lock($resource));
    }

    public function testSuccessLockInvalidResource()
    {
        $resourceInStore = $this->createMock(LockResource::class);
        $resourceInStore
            ->method('isValid')
            ->willReturn(false);

        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('get')
            ->willReturn(serialize($resourceInStore));

        $store->expects($this->once())
            ->method('set')
            ->willReturn(true);

        $locker = new Locker($store);
        $resource = $this->createMock(LockResource::class);
        $this->assertTrue($locker->lock($resource));
    }

    public function testFailLockValidResource()
    {
        $resourceInStore = $this->createMock(LockResource::class);
        $resourceInStore
            ->method('isValid')
            ->willReturn(true);

        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('get')
            ->willReturn(serialize($resourceInStore));

        $store->expects($this->never())
            ->method('set');

        $locker = new Locker($store);
        $resource = $this->createMock(LockResource::class);
        $this->assertFalse($locker->lock($resource));
    }

    public function testSuccessUnlock()
    {
        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('remove')
            ->willReturn(true);

        $locker = new Locker($store);
        $resource = $this->createMock(LockResource::class);
        $this->assertTrue($locker->unlock($resource));
    }

    public function testFailUnlock()
    {
        $store = $this->createMock(LockStoreInterface::class);
        $store->expects($this->once())
            ->method('remove')
            ->willReturn(false);

        $locker = new Locker($store);
        $resource = $this->createMock(LockResource::class);
        $this->assertFalse($locker->unlock($resource));
    }

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
