<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

use Siganushka\Contracts\Registry\Exception\AbstractionNotFoundException;
use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;

/**
 * @see https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-index
 */
class ServiceRegistry implements ServiceRegistryInterface
{
    /**
     * Abstraction that services need to implement.
     */
    private string $abstraction;

    /**
     * Services for registry.
     *
     * @var array<string, object>
     */
    private array $services = [];

    /**
     * Abstraction interface for construct.
     *
     * @param iterable<int|string, object> $serviceIterator
     *
     * @throws AbstractionNotFoundException
     */
    public function __construct(string $abstraction, iterable $serviceIterator = [])
    {
        if (false === interface_exists($abstraction) && false === class_exists($abstraction)) {
            throw new AbstractionNotFoundException($this, $abstraction);
        }

        $this->abstraction = $abstraction;

        foreach ($serviceIterator as $serviceId => $service) {
            if ($service instanceof AliasableInterface) {
                $serviceId = $service->getAlias();
            }

            if (!\is_string($serviceId)) {
                $serviceId = \get_class($service);
            }

            $this->register($serviceId, $service);
        }
    }

    public function register(string $serviceId, object $service): self
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

    public function unregister(string $serviceId): self
    {
        if (!$this->has($serviceId)) {
            throw new ServiceNonExistingException($this, $serviceId);
        }

        unset($this->services[$serviceId]);

        return $this;
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
