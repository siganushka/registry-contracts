<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

use Siganushka\Contracts\Registry\Exception\AbstractionNotFoundException;
use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;

class ServiceRegistry implements ServiceRegistryInterface
{
    /**
     * Services for registry.
     *
     * @var array
     */
    protected $services = [];

    /**
     * Abstraction that services need to implement.
     *
     * @var string
     */
    private $abstraction;

    /**
     * Abstraction interface for construct.
     *
     * @throws AbstractionNotFoundException
     */
    public function __construct(string $abstraction, iterable $serviceIterator = [])
    {
        if (!interface_exists($abstraction)) {
            throw new AbstractionNotFoundException($this, $abstraction);
        }

        $this->abstraction = $abstraction;

        // register for tagged iterator
        foreach ($serviceIterator as $serviceId => $service) {
            if ($service instanceof AliasableInterface) {
                $this->registerForAliasable($service);
            } else {
                $this->register($serviceId, $service);
            }
        }
    }

    public function register(string $serviceId, object $service): ServiceRegistryInterface
    {
        if (!$service instanceof $this->abstraction) {
            throw new ServiceUnsupportedException($this, $serviceId);
        }

        if ($this->has($serviceId)) {
            throw new ServiceExistingException($this, $serviceId);
        }

        $this->services[$serviceId] = $service;

        return $this;
    }

    public function registerForAliasable(AliasableInterface $service): ServiceRegistryInterface
    {
        return $this->register($service->getAlias(), $service);
    }

    public function unregister(string $serviceId): void
    {
        if (!$this->has($serviceId)) {
            throw new ServiceNonExistingException($this, $serviceId);
        }

        unset($this->services[$serviceId]);
    }

    public function has(string $serviceId): bool
    {
        return \array_key_exists($serviceId, $this->services);
    }

    public function get(string $serviceId): object
    {
        if (!$this->has($serviceId)) {
            throw new ServiceNonExistingException($this, $serviceId);
        }

        return $this->services[$serviceId];
    }

    public function clear(): void
    {
        $this->services = [];
    }

    public function all(): array
    {
        return $this->services;
    }

    public function getServiceIds(): array
    {
        return array_map('strval', array_keys($this->services));
    }
}
