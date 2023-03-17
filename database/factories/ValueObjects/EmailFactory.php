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
    public function create(): Email
    {
        $email = (new ReflectionClass(Email::class))->newInstanceWithoutConstructor();
        $this->setValue($email, $this->faker->email());

        return $email;
    }
}
