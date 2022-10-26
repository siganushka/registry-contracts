<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Fixtures;

class BarService implements TestInterface
{
    public function testMethodCalledGetClassName(): string
    {
        return static::class;
    }
}
