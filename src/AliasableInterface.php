<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

interface AliasableInterface
{
    /**
     * Returns alias of service in registry.
     */
    public function getAlias(): string;
}
