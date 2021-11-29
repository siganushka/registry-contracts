<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\Contracts\Registry\AbstractServiceRegistry;
use Siganushka\Contracts\Registry\Exception\AbstractionNotFoundException;
use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;
use Siganushka\Contracts\Registry\ServiceRegistryInterface;
use Siganushka\Contracts\Registry\Tests\Fixtures\BarService;
use Siganushka\Contracts\Registry\Tests\Fixtures\FooService;
use Siganushka\Contracts\Registry\Tests\Fixtures\TestServiceRegistry;

/**
 * @internal
 * @coversNothing
 */
final class ServiceRegistryTest extends TestCase
{
    public function testRegister(): void
    {
        $foo = new FooService();
        $bar = new BarService();

        $registry = new TestServiceRegistry();
        $ret1 = $registry->register('test', $foo);
        $ret2 = $registry->register($bar::getAlias(), $bar);

        static::assertInstanceOf(ServiceRegistryInterface::class, $ret1);
        static::assertInstanceOf(ServiceRegistryInterface::class, $ret2);

        static::assertSame(['test', 'bar'], $registry->getServiceIds());
        static::assertTrue($registry->has('test'));
        static::assertTrue($registry->has('bar'));
        static::assertSame($foo, $registry->get('test'));
        static::assertSame($bar, $registry->get('bar'));

        $registry->unregister('test');
        static::assertSame(['bar'], $registry->getServiceIds());
    }

    public function testAbstractionNotFoundException(): void
    {
        $this->expectException(AbstractionNotFoundException::class);
        $this->expectExceptionMessage('Abstraction NotFoundInterface for TestRegistry could not be found.');

        $this->getMockForAbstractClass(AbstractServiceRegistry::class, ['NotFoundInterface'], 'TestRegistry');
    }

    public function testServiceUnsupportedException(): void
    {
        $this->expectException(ServiceUnsupportedException::class);
        $this->expectExceptionMessage(sprintf('Service test for registry %s is unsupported.', TestServiceRegistry::class));

        $registry = new TestServiceRegistry();
        $registry->register('test', new \stdClass());
    }

    public function testServiceExistingException(): void
    {
        $this->expectException(ServiceExistingException::class);
        $this->expectExceptionMessage(sprintf('Service test for registry %s already exists.', TestServiceRegistry::class));

        $registry = new TestServiceRegistry();
        $registry->register('test', new FooService());
        $registry->register('test', new FooService());
    }

    public function testServiceNonExistingException(): void
    {
        $this->expectException(ServiceNonExistingException::class);
        $this->expectExceptionMessage(sprintf('Service not_found_service_id for registry %s does not exist.', TestServiceRegistry::class));

        $registry = new TestServiceRegistry();
        $registry->get('not_found_service_id');
    }
}
