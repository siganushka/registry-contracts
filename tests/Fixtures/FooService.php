<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Fixtures;

class FooService implements TestInterface
{
    public function testMethodCalledGetClassName(): string
    {
        return static::class;
    }
}
