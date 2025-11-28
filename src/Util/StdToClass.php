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

use function gettype;

use Jasny\TypeCast;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionObject;
use stdClass;

use function substr_count;

/**
 * Helper to create a model class from an stdClass.
 *
 * ```php
 * <?php
 *
 * class Custom
 * {
 *     use StdToClass;
 * }
 * ```
 */
trait StdToClass
{
    /**
     * Undocumented function.
     */
    public static function stdToClass(stdClass $std): self
    {
        $typeCast = new TypeCast();
        $docBlockFactory = DocBlockFactory::createInstance();
        $new = new self();
        $newObj = new ReflectionClass($new);
        $stdObj = new ReflectionObject($std);

        foreach ($stdObj->getProperties() as $property) {
            $name = $property->getName();

            if ($newObj->hasProperty($name) === true) {
                /** @var mixed $value */
                $value = $std->{$name};

                $newProperty = $newObj->getProperty($name);
                $doc = $newProperty->getDocComment();
                $docblock = $docBlockFactory->create($doc ?: '/** */');
                $tags = $docblock->getTagsByName('var');
                $valueType = gettype($value);

                if ($valueType === 'boolean') {
                    $valueType = 'bool';
                }

                if ($valueType === 'integer') {
                    $valueType = 'int';
                }

                if ($newProperty->getType()) {
                    /** @var ReflectionNamedType $type */
                    $type = $newProperty->getType();
                    $varType = $type->getName();

                    if (
                        $varType
                        && substr_count($varType, $valueType) <= 0
                        && $valueType !== 'object'
                         && $valueType !== 'array'
                    ) {
                        $new->{$name} = $typeCast->to($varType)->cast($value);

                        continue;
                    }

                    if ($varType === $valueType || $valueType === '') {
                        $new->{$name} = $value;
                    }
                }

                /** @var Var_ $var */
                foreach ($tags as $var) {
                    $varType = $var->getType();

                    if ($varType !== null) {
                        // $varType = $varType;
                        $varType = $varType->__toString();
                    }

                    if (
                        $varType
                        && substr_count($varType, $valueType) <= 0
                        && $valueType !== 'object'
                         && $valueType !== 'array'
                    ) {
                        $new->{$name} = $typeCast->to($varType)->cast($value);
                    }
                }
            }
        }

        return $new;
    }
}
