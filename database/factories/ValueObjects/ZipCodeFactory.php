<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;
use ReflectionClass;
use ReflectionException;

final class ZipCodeFactory extends StringValueObjectFactory
{
    /**
     * @throws ReflectionException
     */
    public function random(): ZipCode
    {
        $zipCode = (new ReflectionClass(ZipCode::class))->newInstanceWithoutConstructor();
        $this->setValue($zipCode, $this->faker->postcode());

        return $zipCode;
    }
}
