<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\Contracts\Registry\Exception\AbstractionNotFoundException;
use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;
use Siganushka\Contracts\Registry\ServiceRegistry;
use Siganushka\Contracts\Registry\ServiceRegistryInterface;
use Siganushka\Contracts\Registry\Tests\Mock\BarService;
use Siganushka\Contracts\Registry\Tests\Mock\BazService;
use Siganushka\Contracts\Registry\Tests\Mock\FooService;
use Siganushka\Contracts\Registry\Tests\Mock\TestInterface;

final class ServiceRegistryTest extends TestCase
{
    public function testAll(): void
    {
        $foo = new FooService();
        $bar = new BarService();
        $baz = new BazService();

        $registry = new ServiceRegistry(TestInterface::class);
        static::assertInstanceOf(ServiceRegistryInterface::class, $registry->register('foo', $foo));
        static::assertInstanceOf(ServiceRegistryInterface::class, $registry->register('bar', $bar));
        static::assertInstanceOf(ServiceRegistryInterface::class, $registry->register('baz', $baz));

        static::assertSame(['foo', 'bar', 'baz'], $registry->getServiceIds());
        static::assertSame($foo, $registry->get('foo'));
        static::assertSame($bar, $registry->get('bar'));
        static::assertSame($baz, $registry->get('baz'));

        $registry->unregister('bar');
        static::assertSame(['foo', 'baz'], $registry->getServiceIds());

        $registry->clear();
        static::assertSame([], $registry->all());
        static::assertSame([], $registry->getServiceIds());
    }

    public function testServiceIterator(): void
    {
        $serviceIterator = [
            3 => new FooService(),
            'bar' => new BarService(),
            'baz111' => new BazService(),
        ];

        $registry = new ServiceRegistry(TestInterface::class, $serviceIterator);
        static::assertSame([FooService::class, 'bar', 'baz'], $registry->getServiceIds());
        static::assertSame($serviceIterator[3], $registry->get(FooService::class));
        static::assertSame($serviceIterator['bar'], $registry->get('bar'));
        static::assertSame($serviceIterator['baz111'], $registry->get('baz'));

        $registry->unregister(FooService::class);
        static::assertSame(['bar', 'baz'], $registry->getServiceIds());

        $registry->clear();
        static::assertSame([], $registry->all());
        static::assertSame([], $registry->getServiceIds());
    }

    public function testAbstractionNonInterface(): void
    {
        static::assertInstanceOf(ServiceRegistryInterface::class, new ServiceRegistry(\stdClass::class));
    }

    public function testAbstractionNotFoundException(): void
    {
        $this->expectException(AbstractionNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Abstraction interface (or class) "%s" for "%s" could not be found.', 'NotFoundInterface', ServiceRegistry::class));

        new ServiceRegistry('NotFoundInterface');
    }

    public function testServiceUnsupportedException(): void
    {
        $this->expectException(ServiceUnsupportedException::class);
        $this->expectExceptionMessage(sprintf('Service "test" for "%s" is unsupported.', ServiceRegistry::class));

        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('test', new \stdClass());
    }

    public function testServiceExistingException(): void
    {
        $this->expectException(ServiceExistingException::class);
        $this->expectExceptionMessage(sprintf('Service "test" for "%s" already exists.', ServiceRegistry::class));

        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('test', new FooService());
        $registry->register('test', new FooService());
    }

    public function testServiceNonExistingException(): void
    {
        $this->expectException(ServiceNonExistingException::class);
        $this->expectExceptionMessage(sprintf('Service "not_found_service_id" for "%s" does not exist.', ServiceRegistry::class));

        $registry = new ServiceRegistry(TestInterface::class);
        $registry->get('not_found_service_id');
    }
}
