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

        foreach ($serviceIterator as $id => $service) {
            if ($service instanceof AliasableInterface) {
                $id = $service->getAlias();
            }

            if (!\is_string($id)) {
                $id = \get_class($service);
            }

            $this->register($id, $service);
        }
    }

    public function register(string $id, object $service): self
    {
        if (!$service instanceof $this->abstraction) {
            throw new ServiceUnsupportedException($this, $id);
        }

        if ($this->has($id)) {
            throw new ServiceExistingException($this, $id);
        }

        $this->services[$id] = $service;

        return $this;
    }

    public function unregister(string $id): self
    {
        if (!$this->has($id)) {
            throw new ServiceNonExistingException($this, $id);
        }

        unset($this->services[$id]);

        return $this;
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->services);
    }

    public function get(string $id): object
    {
        if (!$this->has($id)) {
            throw new ServiceNonExistingException($this, $id);
        }

        return $this->services[$id];
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
