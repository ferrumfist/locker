# Locking library

PHP library performs resource locking

# Install

```composer
composer require ferrumfist/locker
```

# Usage

```php
$store = new FileStore(__DIR__ . '/stores');
$locker = new Locker($store);
$resource = new LockResource('ResourceName');

if($locker->lock($resource)){
    echo "Resource was successfully locked" . PHP_EOL;
} else {
    echo "Resource could not be locked" . PHP_EOL;
}
```
