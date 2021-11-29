<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceNonExistingException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $serviceId)
    {
        parent::__construct($registry, sprintf('Service "%s" for "%s" does not exist.', $serviceId, \get_class($registry)));
    }
}
