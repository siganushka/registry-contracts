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
use Siganushka\Contracts\Registry\Tests\Fixtures\BarService;
use Siganushka\Contracts\Registry\Tests\Fixtures\FooService;
use Siganushka\Contracts\Registry\Tests\Fixtures\TestInterface;

/**
 * @internal
 * @coversNothing
 */
final class ServiceRegistryTest extends TestCase
{
    public function testAll(): void
    {
        $foo = new FooService();
        $bar = new BarService();

        $registry = new ServiceRegistry(TestInterface::class);
        static::assertInstanceOf(ServiceRegistryInterface::class, $registry->register('test', $foo));
        static::assertInstanceOf(ServiceRegistryInterface::class, $registry->registerForAliasable($bar));

        static::assertSame(['test' => $foo, 'bar' => $bar], $registry->all());
        static::assertSame(['test', 'bar'], $registry->getServiceIds());
        static::assertTrue($registry->has('test'));
        static::assertTrue($registry->has('bar'));
        static::assertSame($foo, $registry->get('test'));
        static::assertSame($bar, $registry->get('bar'));

        $registry->unregister('test');
        static::assertSame(['bar'], $registry->getServiceIds());

        $registry->clear();
        static::assertSame([], $registry->all());
        static::assertSame([], $registry->getServiceIds());
    }

    public function testServiceIterator()
    {
        $serviceIterator = [
            'foo' => new FooService(),
            'bar222' => new BarService(),
        ];

        $registry = new ServiceRegistry(TestInterface::class, $serviceIterator);
        static::assertSame(array_values($serviceIterator), array_values($registry->all()));
        static::assertSame(['foo', 'bar'], $registry->getServiceIds());
        static::assertTrue($registry->has('foo'));
        static::assertTrue($registry->has('bar'));
        static::assertFalse($registry->has('bar222'));
        static::assertSame($serviceIterator['foo'], $registry->get('foo'));
        static::assertSame($serviceIterator['bar222'], $registry->get('bar'));
    }

    public function testAbstractionNotFoundException(): void
    {
        $this->expectException(AbstractionNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Abstraction class "NotFoundInterface" for "%s" could not be found.', ServiceRegistry::class));

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
