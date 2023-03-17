<?php declare(strict_types=1);

namespace Database\Factories;

use Faker\Generator as Faker;

abstract class Factory
{
    public function __construct(
        protected readonly Faker $faker,
    ) {}
}
