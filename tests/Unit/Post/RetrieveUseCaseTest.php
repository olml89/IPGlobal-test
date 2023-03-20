<?php declare(strict_types=1);

namespace Test\Unit\Post;

use Database\Factories\ValueObjects\EmailFactory;
use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\IPGlobalTest\Post\Application\Retrieve\RetrieveUseCase;
use olml89\IPGlobalTest\Post\Domain\PostNotFoundException;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class RetrieveUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly Connection $database;
    private readonly RetrieveUseCase $retrieveUseCase;
    private readonly User $user;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->faker = $this->app->get(Faker::class);
        $this->database = $this->app->get(Connection::class);
        $this->retrieveUseCase = $this->app->get(RetrieveUseCase::class);

        $this->setUpUser();
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    private function setUpUser(): void
    {
        /** @var EmailFactory $emailFactory */
        $emailFactory = $this->app->get(EmailFactory::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->app->get(UserRepository::class);

        $this->user = $userRepository->getByEmail(
            $emailFactory->create('johndeere@fake-mail.com')
        );
    }

    public function test_invalid_id_throws_an_invalid_uuid_exception(): void
    {
        $invalidUuid = 'invalid_uuid';

        $this->expectException(InvalidUuidException::class);

        $this->retrieveUseCase->retrieve($invalidUuid);
    }

    public function test_unexisting_post_id_throws_a_post_not_found_exception(): void
    {
        $unexistingUuid = $this->faker->uuid();

        $this->expectException(PostNotFoundException::class);

        $this->retrieveUseCase->retrieve($unexistingUuid);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function test_valid_request_returns_the_requested_post(): void
    {
        $uuid = $this->database->getDoctrineConnection()->fetchOne(
            query: 'select id from posts where user_id = :user_id',
            params: ['user_id' => (string)$this->user->id()],
        );

        $postResult = $this->retrieveUseCase->retrieve($uuid);

        $this->assertEquals($uuid, $postResult->id);
        $this->assertEquals((string)$this->user->id(), $postResult->user->id);
    }
}
