<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\UuidFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\User\Domain\User;
use ReflectionException;

final class PostFactory
{
    public function __construct(
        private readonly Faker $faker,
        private readonly UuidFactory $uuidFactory,
        private readonly UserFactory $userFactory,
    ) {}

    /**
     * @throws ReflectionException
     */
    public function random(): Post
    {
        return $this->withUser(
            user: $this->userFactory->random(),
        );
    }

    /**
     * @throws ReflectionException
     */
    public function withUser(User $user): Post
    {
        return new Post(
            id: $this->uuidFactory->random(),
            user: $user,
            title: new StringValueObject($this->faker->text(50)),
            body: new StringValueObject($this->faker->text()),
        );
    }
}
