<?php declare(strict_types=1);

namespace Tests\Unit\Post;

use Database\Factories\ValueObjects\EmailFactory;
use Faker\Generator as Faker;
use Mockery\MockInterface;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Post\Application\Publish\PublishData;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class PublishUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly PostRepository $postRepository;
    private readonly PublishData $publishData;
    private readonly User $user;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->faker = $this->app->get(Faker::class);
        $this->postRepository = $this->app->get(PostRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->app->get(UserRepository::class);

        /** @var EmailFactory $emailFactory */
        $emailFactory = $this->app->get(EmailFactory::class);

        $this->user = $userRepository->getByEmail(
            $emailFactory->create('johndeere@fake-mail.com')
        );

        $this->publishData = new PublishData(
            title: $this->faker->text(50),
            body: $this->faker->text(),
        );
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    private function generateUseCase(string $uuid): PublishUseCase
    {
        $this->mock(UuidGenerator::class, function(MockInterface $mock) use($uuid): void {
            $mock->shouldReceive('random')
                ->once()
                ->andReturn($uuid);
        });

        return new PublishUseCase(
            uuidGenerator: $this->app->get(UuidGenerator::class),
            postRepository: $this->postRepository,
        );
    }

    public function test_result_includes_post_id_and_user(): void
    {
        $uuid = $this->faker->uuid();
        $publishUseCase = $this->generateUseCase($uuid);

        $publishResult = $publishUseCase->publish(
            publishData: $this->publishData,
            user: $this->user,
        );

        $this->assertDatabaseCount('posts', 2);
        $this->assertEquals($uuid, $publishResult->id);
        $this->assertEquals((string)$this->user->id(), $publishResult->user->id);
    }
}
