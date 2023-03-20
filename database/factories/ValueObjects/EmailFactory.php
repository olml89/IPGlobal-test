<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\IPGlobalTest\User\Domain\Email\Email;
use ReflectionClass;
use ReflectionException;

final class EmailFactory extends StringValueObjectFactory
{
    /**
     * @throws ReflectionException
     */
    public function random(): Email
    {
        return $this->create($this->faker->email());
    }

    /**
     * @throws ReflectionException
     */
    public function create(string $value): Email
    {
        $email = (new ReflectionClass(Email::class))->newInstanceWithoutConstructor();
        $this->setValue($email, $value);

        return $email;
    }
}
