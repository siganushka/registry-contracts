# Registry Contracts

A set of abstractions for registry pattern.

### Installation

```bash
$ composer require siganushka/registry-contracts:dev-main
```

### Usage

```php
// ./src/Channel/ChannelInterface.php

interface ChannelInterface
{
    public function methodA(): string;
    public function methodB(): string;
    public function methodN(): string;

    // ...
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
namespace Siganushka\Contracts\Registry\ServiceRegistry;

$registry = new ServiceRegistry(ChannelInterface::class);
$registry->register('foo', new FooChannel());
$registry->register('bar', new BarChannel());

$registry->get('foo');      // return instanceof FooChannel
$registry->has('bar');      // return true
$registry->all();           // return array of instanceof ChannelInterface
$registry->getServiceIds(); // return ['foo', 'bar']
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
namespace Siganushka\Contracts\Registry\ServiceRegistry;

$registry = new ServiceRegistry(ChannelInterface::class);
$registry->registerForAliasable(new FooChannel());

$registry->get('foo');      // return instanceof FooChannel
$registry->has('foo');      // return true
$registry->all();           // return array of instanceof ChannelInterface
$registry->getServiceIds(); // return ['foo']
```

### Tests

```bash
$ php vendor/bin/simple-phpunit --debug
```
