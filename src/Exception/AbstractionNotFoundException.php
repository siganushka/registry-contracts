<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class AbstractionNotFoundException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $abstraction)
    {
        parent::__construct($registry, sprintf('Abstraction interface (or class) "%s" for "%s" could not be found.', $abstraction, \get_class($registry)));
    }
}
