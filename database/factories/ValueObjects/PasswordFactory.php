<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use Database\Factories\Factory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\Password\Password;

final class PasswordFactory extends Factory
{
    private readonly Hasher $hasher;

    public function __construct(Faker $faker, Hasher $hasher)
    {
        parent::__construct($faker);

        $this->hasher = $hasher;
    }

    public function create(string $plain): Password
    {
        return Password::create($plain, $this->hasher);
    }

    public function random(): Password
    {
        return $this->create($this->faker->password());
    }
}
