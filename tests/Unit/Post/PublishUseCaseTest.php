<?php declare(strict_types=1);

namespace Tests\Unit\Post;

use Database\Factories\ValueObjects\EmailFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Post\Application\Publish\PublishData;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class PublishUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly PublishUseCase $publishUseCase;
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

        $this->publishUseCase = $this->app->get(PublishUseCase::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->app->get(UserRepository::class);

        /** @var EmailFactory $emailFactory */
        $emailFactory = $this->app->get(EmailFactory::class);

        $this->user = $userRepository->getByEmail(
            $emailFactory->create('johndeere@fake-mail.com')
        );

        /** @var Faker $faker */
        $faker = $this->app->get(Faker::class);

        $this->publishData = new PublishData(
            title: $faker->text(50),
            body: $faker->text(),
        );
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    public function test_a_new_post_is_created_with_the_authenticated_user(): void
    {
        $publishResult = $this->publishUseCase->publish(
            publishData: $this->publishData,
            user: $this->user,
        );

        $this->assertDatabaseCount('posts', 2);
        $this->assertEquals((string)$this->user->id(), $publishResult->user->id);
    }
}
