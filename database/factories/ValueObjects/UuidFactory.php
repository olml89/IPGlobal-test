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
    public function random(): Uuid
    {
        return $this->create(
            $this->faker->uuid(),
        );
    }

    /**
     * @throws ReflectionException
     */
    public function create(string $value): Uuid
    {
        $uuid = (new ReflectionClass(Uuid::class))->newInstanceWithoutConstructor();
        $this->setValue($uuid, $value);

        return $uuid;
    }
}
