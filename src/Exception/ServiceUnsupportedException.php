<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceUnsupportedException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $serviceId)
    {
        parent::__construct($registry, sprintf('Service "%s" for "%s" is unsupported.', $serviceId, \get_class($registry)));
    }
}
