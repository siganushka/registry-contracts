<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

use Siganushka\Contracts\Registry\Exception\ServiceExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceNonExistingException;
use Siganushka\Contracts\Registry\Exception\ServiceUnsupportedException;

interface ServiceRegistryInterface
{
    /**
     * Register a service.
     *
     * @param string $serviceId Service ID
     * @param object $service   Service Object
     *
     * @throws ServiceUnsupportedException
     * @throws ServiceExistingException
     */
    public function register(string $serviceId, object $service): self;

    /**
     * Unregister a service.
     *
     * @param string $serviceId Service ID
     *
     * @throws ServiceNonExistingException
     */
    public function unregister(string $serviceId): self;

    /**
     * Returns true if the given service is defined.
     *
     * @param string $serviceId Service ID
     */
    public function has(string $serviceId): bool;

    /**
     * Gets a service.
     *
     * @param string $serviceId Service ID
     *
     * @throws ServiceNonExistingException
     */
    public function get(string $serviceId): object;

    /**
     * Gets all services.
     *
     * @return array<string, object>
     */
    public function all(): array;

    /**
     * Gets all service ids.
     *
     * @return array<string>
     */
    public function getServiceIds(): array;
}
