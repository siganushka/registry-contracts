<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceExistingException extends ServiceRegistryException
{
    public function __construct(ServiceRegistryInterface $registry, string $id)
    {
        parent::__construct($registry, sprintf('Service "%s" for "%s" already exists.', $id, \get_class($registry)));
    }
}
