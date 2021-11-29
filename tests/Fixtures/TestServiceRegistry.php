<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Tests\Fixtures;

use Siganushka\Contracts\Registry\AbstractServiceRegistry;

class TestServiceRegistry extends AbstractServiceRegistry
{
    public function __construct()
    {
        parent::__construct(TestInterface::class);
    }
}
