<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Database\Factories\Factory;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use ReflectionClass;

abstract class StringValueObjectFactory extends Factory
{
    protected function setValue(StringValueObject $valueObject, string $value): void
    {
        $reflectionClass = new ReflectionClass(StringValueObject::class);
        $reflectionClass->getProperty('value')->setAccessible(true);
        $reflectionClass->getProperty('value')->setValue($valueObject, $value);
    }
}
