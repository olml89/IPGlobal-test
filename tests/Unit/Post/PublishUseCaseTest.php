<?php declare(strict_types=1);

namespace Tests\Unit\Post;

use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use Mockery\MockInterface;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Post\Application\Publish\PublishData;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Post\Domain\PostStorageException;
use Tests\TestCase;

final class PublishUseCaseTest extends TestCase
{
    private readonly Faker $faker;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function generatePublishUseCaseWithUuid(string $uuid): PublishUseCase
    {
        $uuidGenerator = $this->mock(UuidGenerator::class, function (MockInterface $mock) use($uuid): void {
            $mock->shouldReceive('random')->once()->andReturn($uuid);
        });

        return new PublishUseCase(
            uuidGenerator: $uuidGenerator,
            postRepository: $this->app->get(PostRepository::class),
            userFactory: $this->app->get(UserFactory::class),
        );
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function test_that_invalid_id_generates_error(): void
    {
        $invalidUuid = 'invalid';
        $publishUseCase = $this->generatePublishUseCaseWithUuid($invalidUuid);

        $publishData = new PublishData(
            title: $this->faker->title(),
            body: $this->faker->text(),
        );

        $this->expectException(PostStorageException::class);

        $publishUseCase->publish($publishData);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function test_result_includes_post_id(): void
    {
        $uuid = $this->faker->uuid();
        $publishUseCase = $this->generatePublishUseCaseWithUuid($uuid);

        $publishData = new PublishData(
            title: $this->faker->title(),
            body: $this->faker->text(),
        );

        $publishResult = $publishUseCase->publish($publishData);;

        $this->assertEquals($uuid, $publishResult->id);
    }
}
