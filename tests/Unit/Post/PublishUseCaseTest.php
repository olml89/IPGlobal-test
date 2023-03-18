<?php declare(strict_types=1);

namespace Tests\Unit\Post;

use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use Mockery\MockInterface;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Post\Application\Publish\PublishData;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Post\Domain\PostStorageException;
use olml89\IPGlobalTest\User\Domain\User;
use Tests\TestCase;

final class PublishUseCaseTest extends TestCase
{
    private readonly Faker $faker;
    private readonly UserFactory $userFactory;
    private readonly PublishUseCase $publishUseCase;
    private readonly PublishData $publishData;
    private readonly User $user;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
        $this->userFactory = $this->app->get(UserFactory::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function prepareUseCase(string $uuid): void
    {
        $uuidGenerator = $this->mock(UuidGenerator::class, function (MockInterface $mock) use($uuid): void {
            $mock->shouldReceive('random')->once()->andReturn($uuid);
        });

        $this->publishUseCase = new PublishUseCase(
            uuidGenerator: $uuidGenerator,
            postRepository: $this->app->get(PostRepository::class),
            userFactory: $this->app->get(UserFactory::class),
        );
    }

    /**
     * @throws \ReflectionException
     */
    private function prepareTestingData(): void
    {
        $this->publishData = new PublishData(
            title: $this->faker->title(),
            body: $this->faker->text(),
        );

        $this->user = $this->userFactory->create();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function test_that_invalid_id_generates_error(): void
    {
        $invalidUuid = 'invalid';
        $this->prepareUseCase($invalidUuid);
        $this->prepareTestingData();

        $this->expectException(PostStorageException::class);

        $this->publishUseCase->publish(
            $this->publishData,
            $this->user
        );
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function test_result_includes_post_id(): void
    {
        $uuid = $this->faker->uuid();
        $this->prepareUseCase($uuid);
        $this->prepareTestingData();

        $publishResult = $this->publishUseCase->publish(
            $this->publishData,
            $this->user
        );

        $this->assertEquals($uuid, $publishResult->id);
    }
}
