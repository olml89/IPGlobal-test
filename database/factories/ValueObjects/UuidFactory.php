<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

final class UuidFactory extends StringValueObjectFactory
{
    /**
     * @throws ReflectionException
     */
    public function create(): Uuid
    {
        $uuid = (new ReflectionClass(Uuid::class))->newInstanceWithoutConstructor();
        $this->setValue($uuid, $this->faker->uuid());

        return $uuid;
    }
}
