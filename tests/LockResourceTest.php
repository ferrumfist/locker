<?php

use PHPUnit\Framework\TestCase;
use Locker\Resources\LockResource;

class LockResourceTest extends TestCase
{
    public function testIsValid()
    {
        $resource = new LockResource('TestResource');
        $resource->setTTL(5);

        $this->assertSame('TestResource', $resource->getName());

        $this->assertTrue($resource->isValid());
    }

    public function testNotValid()
    {
        $resource = new LockResource('TestResource');
        $resource->setExpire(time() - 5);

        $this->assertFalse($resource->isValid());
    }
}
