<?php

require '../vendor/autoload.php';

use Locker\Locker;
use Locker\Store\LockStoreInterface;
use Locker\Store\FileStore;
use Locker\Resources\LockResource;

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    LockStoreInterface::class => function()
    {
        return new FileStore(__DIR__ . '/stores');
    }
]);
$container = $builder->build();

try {
    $locker = $container->get(Locker::class);
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}

$resource = new LockResource('Test');

function lockExec(Locker $locker, LockResource $resource){
    if($locker->lock($resource)){
        echo "Resource was successfully locked" . PHP_EOL;
    } else {
        echo "Resource could not be locked" . PHP_EOL;
    }
}

lockExec($locker, $resource); // true
lockExec($locker, $resource); // false
$locker->unlock($resource);
lockExec($locker, $resource); // true again
$locker->unlock($resource);
