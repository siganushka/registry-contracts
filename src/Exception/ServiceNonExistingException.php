<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceNonExistingException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $id)
    {
        parent::__construct($registry, sprintf('Service "%s" for "%s" does not exist.', $id, \get_class($registry)));
    }
}
