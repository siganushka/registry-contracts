<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceUnsupportedException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $id)
    {
        parent::__construct($registry, sprintf('Service "%s" for "%s" is unsupported.', $id, \get_class($registry)));
    }
}
