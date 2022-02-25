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
    public function methodB(): int;
 
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
use Siganushka\Contracts\Registry\ServiceRegistry;

$registry = new ServiceRegistry(ChannelInterface::class);
$registry->register('foo', new FooChannel());
$registry->register('bar', new BarChannel());

$registry->get('foo');      // return instanceof FooChannel
$registry->has('bar');      // return true
$registry->all();           // return array of instanceof ChannelInterface
$registry->getServiceIds(); // return ['foo', 'bar']
```

### Using alias

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
use Siganushka\Contracts\Registry\ServiceRegistry;

$channels = [
    new FooChannel()
];

// the second argument is type iterable of $services 
$registry = new ServiceRegistry(ChannelInterface::class, $channels);

$registry->get('foo');      // return instanceof FooChannel
$registry->has('foo');      // return true
$registry->all();           // return array of instanceof ChannelInterface
$registry->getServiceIds(); // return ['foo']
```

### Using symfony tagged services

```php
// ./src/Channel/ChannelRegistry.php

class ChannelRegistry extends ServiceRegistry
{
    public function __construct(iterable $channels)
    {
        parent::__construct(ChannelInterface::class, $channels);
    }
}
```

```yaml
// ./config/services.yaml

services:
    _instanceof:
        App\Channel\ChannelInterface
            tags: [ app.channel ]

    App\Channel\ChannelRegistry:
        arguments: [ !tagged_iterator app.channel ]
```

```php
// ./src/Controller/BazController.php

class BazController extends AbstractController
{
    public function index(ChannelRegistry $registry)
    {
        $foo = $registry->get(FooChannel::class);
        // or $registry->get('foo') if using alias
        // $foo is instanceof FooChannel
    }
}
```

More details: https://symfony.com/doc/current/service_container/tags.html#reference-tagged-services

### Tests

```bash
$ php vendor/bin/simple-phpunit --debug
```
