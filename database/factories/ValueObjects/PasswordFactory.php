<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Database\Factories\Factory;
use olml89\IPGlobalTest\User\Domain\Password\Password;
use ReflectionClass;

final class PasswordFactory extends Factory
{
    public function create(): Password
    {
        $reflectionClass = new ReflectionClass(Password::class);
        $password = $reflectionClass->newInstanceWithoutConstructor();
        $reflectionClass->getProperty('hash')->setAccessible(true);
        $reflectionClass->getProperty('hash')->setValue($password, $this->faker->uuid());

        return $password;
    }
}
