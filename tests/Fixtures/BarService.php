<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Fixtures;

use Siganushka\Contracts\Registry\AliasableInterface;

class BarService implements TestInterface, AliasableInterface
{
    public function getAlias(): string
    {
        return 'bar';
    }
}
