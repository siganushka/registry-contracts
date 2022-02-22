<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry\Exception;

use Siganushka\Contracts\Registry\ServiceRegistryInterface;

class ServiceRegistryException extends \RuntimeException
{
    protected ServiceRegistryInterface $registry;

    public function __construct(ServiceRegistryInterface $registry, string $message, int $code = 0, \Throwable $previous = null)
    {
        $this->registry = $registry;

        parent::__construct($message, $code, $previous);
    }

    public function getRegistry(): ServiceRegistryInterface
    {
        return $this->registry;
    }
}
