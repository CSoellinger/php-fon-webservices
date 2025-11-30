<?php

/**
 * This file is part of csoellinger/php-fon-webservices.
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

namespace CSoellinger\FonWebservices\Util;

use DateTime;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use stdClass;

/**
 * Helper to deserialize stdClass to model classes.
 */
class Serializer
{
    /**
     * Deserialize stdClass or object to a typed model object.
     *
     * @template T of object
     * @param stdClass|object $data
     * @param class-string<T> $className
     * @return T
     */
    public static function deserialize(object $data, string $className): object
    {
        $reflection = new ReflectionClass($className);
        $instance = $reflection->newInstance();

        /** @var array<string, mixed> $properties */
        $properties = get_object_vars($data);

        /** @psalm-suppress MixedAssignment */
        foreach ($properties as $propertyName => $value) {
            if (!$reflection->hasProperty($propertyName)) {
                continue;
            }

            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);

            $convertedValue = self::convertValue($value, $property);
            $property->setValue($instance, $convertedValue);
        }

        /** @var T */
        return $instance;
    }

    /**
     * Convert a value to match the property type.
     *
     * @param mixed $value
     * @return mixed
     */
    private static function convertValue($value, ReflectionProperty $property)
    {
        $type = $property->getType();

        if (!$type instanceof ReflectionNamedType) {
            return $value;
        }

        $typeName = $type->getName();

        // Handle null values
        if ($value === null && $type->allowsNull()) {
            return null;
        }

        // Handle DateTime conversion
        if ($typeName === 'DateTime' || $typeName === DateTime::class) {
            if ($value instanceof DateTime) {
                return $value;
            }

            if (is_string($value)) {
                return new DateTime($value);
            }

            if ($value instanceof stdClass && isset($value->date) && is_string($value->date)) {
                return new DateTime($value->date);
            }

            if (is_array($value) && isset($value['date']) && is_string($value['date'])) {
                return new DateTime($value['date']);
            }
        }

        // Handle nested object conversion
        if (class_exists($typeName) && ($value instanceof stdClass || is_object($value) || is_array($value))) {
            if (is_array($value)) {
                $value = (object) $value;
            }

            return self::deserialize($value, $typeName);
        }

        // Handle array of objects
        if ($typeName === 'array' && is_array($value)) {
            return $value;
        }

        // Type casting for scalar types
        if ($typeName === 'int' && !is_int($value)) {
            return is_numeric($value) ? (int) $value : 0;
        }

        if ($typeName === 'float' && !is_float($value)) {
            return is_numeric($value) ? (float) $value : 0.0;
        }

        if ($typeName === 'string' && !is_string($value)) {
            return is_scalar($value) ? (string) $value : '';
        }

        if ($typeName === 'bool' && !is_bool($value)) {
            return (bool) $value;
        }

        return $value;
    }
}
