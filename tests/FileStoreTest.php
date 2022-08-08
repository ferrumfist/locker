<?php

use PHPUnit\Framework\TestCase;
use Locker\Store\FileStore;

class FileStoreTest extends TestCase
{
    const STORE_DIR = __DIR__ . '/store';

    public static function setUpBeforeClass(): void
    {
        if (file_exists(self::STORE_DIR)) {
            rmdir(self::STORE_DIR);
        }
        parent::setUpBeforeClass();
    }

    public function testSetGetData()
    {
        $store = new FileStore(self::STORE_DIR);
        $this->assertTrue($store->set('test', 'Some test message'));

        $this->assertSame(
            'Some test message',
            $store->get('test')
        );

        return $store;
    }

    /**
     * @depends testSetGetData
     */
    public function testRemoveData(FileStore $store)
    {
        $this->assertTrue($store->remove('test'));

        self::assertEmpty($store->get('test'));

        return $store;
    }

    /**
     * @depends testRemoveData
     */
    public function testRemoveNonExistKey(FileStore $store)
    {
        $this->assertTrue($store->remove('test'));

        self::assertEmpty($store->get('test'));
    }
}
