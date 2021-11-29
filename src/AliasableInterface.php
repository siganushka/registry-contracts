<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

interface AliasableInterface
{
    /**
     * Returns alias for service.
     */
    public function getAlias(): string;
}
