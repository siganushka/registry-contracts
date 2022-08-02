<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Mock;

class FooService implements TestInterface
{
    public function testMethodCalledGetClassName(): string
    {
        return static::class;
    }
}
