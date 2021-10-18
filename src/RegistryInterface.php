<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;

interface RegistryInterface
{
    /**
     * The service for registry.
     *
     * @throws ServiceUnsupportedException
     * @throws ServiceExistingException
     */
    public function register(object $service): RegistryInterface;

    /**
     * Check service if exists.
     */
    public function has(string $serviceId): bool;

    /**
     * Return service from registry.
     *
     * @throws ServiceNonExistingException
     */
    public function get(string $serviceId): object;

    /**
     * Remove service from registry.
     *
     * @throws ServiceNonExistingException
     */
    public function remove(string $serviceId): void;

    /**
     * Clear all service from regsitry.
     */
    public function clear(): void;

    /**
     * Return key of services from registry.
     */
    public function getKeys(): array;

    /**
     * Return services from registry.
     */
    public function getValues(): array;
}
