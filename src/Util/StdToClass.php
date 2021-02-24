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

use Jasny\TypeCast;
use ReflectionClass;
use ReflectionObject;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Type;
use stdClass;

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
     * @var string Undocumented variable2
     */
    public static $yx = 'Test';

    /**
     * Undocumented function.
     *
     * With extra text.
     *
     * @param stdClass $std stdClass to convert into model class
     *
     * @return self Retttttturrrrrrn
     */
    public static function stdToClass(stdClass $std, int &$x, string $y = 'Test', ?array $z, array ...$a): self
    {
        $typeCast = new TypeCast();
        $docBlockFactory = DocBlockFactory::createInstance();
        $new = new self();
        $newObj = new ReflectionClass($new);
        $stdObj = new ReflectionObject($std);

        foreach ($stdObj->getProperties() as $property) {
            $name = $property->getName();

            /**
             * @psalm-suppress MixedAssignment
             */
            $value = $std->{$name};
            $new->{$name} = $value;

            if ($newObj->hasProperty($name) === true) {
                $newProperty = $newObj->getProperty($name);
                $doc = $newProperty->getDocComment();
                $docblock = $docBlockFactory->create($doc ?: '/** */');
                $tags = $docblock->getTagsByName('var');

                /** @var Var_ $var */
                foreach ($tags as $var) {
                    $valueType = gettype($value);

                    if ($valueType === 'boolean') {
                        $valueType = 'bool';
                    }

                    if ($valueType === 'integer') {
                        $valueType = 'int';
                    }

                    $varType = $var->getType();

                    if ($varType !== null) {
                        /** @var Type $varType */
                        $varType = $varType;
                        $varType = $varType->__toString();
                    }

                    if (
                        $varType &&
                        substr_count($varType, $valueType) <= 0 &&
                        $valueType !== 'object' &&
                         $valueType !== 'array'
                    ) {
                        $new->{$name} = $typeCast->to($varType)->cast($value);
                    }
                }
            }
        }

        return $new;
    }
}
