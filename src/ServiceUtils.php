<?php

declare(strict_types=1);

namespace Siganushka\Contracts\Registry;

class ServiceUtils
{
    public const ALIAS_METHOD = 'getAlias';

    public static function getServiceId(string $className): string
    {
        try {
            $ref = new \ReflectionClass($className);
            $serviceId = $ref->getMethod(self::ALIAS_METHOD)->invoke(null);
        } catch (\ReflectionException $th) {
            return $className;
        }

        if (!\is_string($serviceId)) {
            throw new \InvalidArgumentException(sprintf('Method "%s::%s()" should return string (got "%s")', $className, self::ALIAS_METHOD, get_debug_type($serviceId)));
        }

        return (string) $serviceId;
    }
}
