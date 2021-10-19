# Registry Contracts

A set of abstractions for registry pattern.

### Installation

```bash
$ composer require siganushka/registry-contracts
```

### Usage

```php
// ./src/Channel/ChannelInterface.php

interface ChannelInterface
{
    public function method1(): string;
    public function method2(): string;
    public function method3(): string;
}
```

```php
// ./src/Channel/FooChannel.php

class FooChannel implements ChannelInterface
{
    // ...
}
```

```php
// ./src/Channel/BarChannel.php

class BarChannel implements ChannelInterface
{
    // ...
}
```

```php
// ./src/Channel/ChannelRegistry.php

use Siganushka\Contracts\Registry\AbstractRegistry;

class ChannelRegistry extends AbstractRegistry
{
    public function __construct()
    {
        parent::__construct(ChannelInterface::class);
    }
}
```

```php
$registry = new ChannelRegistry();
$registry->register(new FooChannel());
$registry->register(new BarChannel());

$registry->get(FooChannel::class);  // return instanceof FooChannel
$registry->has(BarChannel::class);  // return true
$registry->getValues();             // return array of instanceof ChannelInterface
$registry->getKeys();               // return ['App\Channel\FooChannel', 'App\Channel\BarChannel']
```

Registry with alias.

```php
// ./src/Channel/FooChannel.php

use Siganushka\Contracts\Registry\AliasableInterface;

class FooChannel implements ChannelInterface, AliasableInterface
{
    public function getAlias(): string
    {
        return 'foo';
    }

    // ...
}
```

```php
// ./src/Channel/BarChannel.php

use Siganushka\Contracts\Registry\AliasableInterface;

class BarChannel implements ChannelInterface, AliasableInterface
{
    public function getAlias(): string
    {
        return 'bar';
    }

    // ...
}
```

```php
$registry = new ChannelRegistry();
$registry->register(new FooChannel());
$registry->register(new BarChannel());

$registry->get('foo');  // return instanceof FooChannel
$registry->has('bar');  // return true
$registry->getValues(); // return array of instanceof ChannelInterface
$registry->getKeys();   // return ['foo', 'bar']
```

### Tests

```bash
$ php vendor/bin/simple-phpunit --debug 
```
