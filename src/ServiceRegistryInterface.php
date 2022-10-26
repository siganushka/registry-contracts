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
     * @param string $id      Service ID
     * @param object $service Service Object
     *
     * @throws ServiceUnsupportedException
     * @throws ServiceExistingException
     */
    public function register(string $id, object $service): self;

    /**
     * Unregister a service.
     *
     * @param string $id Service ID
     *
     * @throws ServiceNonExistingException
     */
    public function unregister(string $id): self;

    /**
     * Returns true if the given service is defined.
     *
     * @param string $id Service ID
     */
    public function has(string $id): bool;

    /**
     * Gets a service.
     *
     * @param string $id Service ID
     *
     * @throws ServiceNonExistingException
     */
    public function get(string $id): object;

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
