<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Publish;

use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Domain\Post;

final class PublishUseCase
{
    public function __construct(
        private readonly Faker $faker,
        private readonly UserFactory $userFactory,
    ) {}

    public function publish(PublishData $publishData): PostResult
    {
        // We simulate the creation of an AutoIncrementalId by the database
        $post = new Post(
            id: new AutoIncrementalId($this->faker->randomNumber()),
            user: $this->userFactory->createWithId($publishData->user_id),
            title: new StringValueObject($publishData->title),
            body: new StringValueObject($publishData->body),
        );

        return new PostResult($post);
    }
}
