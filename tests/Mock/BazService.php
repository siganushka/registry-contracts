<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Mock;

use Siganushka\Contracts\Registry\AliasableInterface;

class BazService implements TestInterface, AliasableInterface
{
    public function testMethodCalledGetClassName(): string
    {
        return static::class;
    }

    public function getAlias(): string
    {
        return 'baz';
    }
}
