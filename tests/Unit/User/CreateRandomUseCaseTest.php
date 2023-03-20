<?php declare(strict_types=1);

namespace Tests\Unit\User;

use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use olml89\IPGlobalTest\User\Application\Create\CreateRandomUseCase;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\UserCreationException;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class CreateRandomUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly Connection $database;
    private readonly Hasher $hasher;
    private readonly CreateRandomUseCase $createRandomUseCase;

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
        $this->hasher = $this->app->get(Hasher::class);
        $this->createRandomUseCase = $this->app->get(CreateRandomUseCase::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    public function test_invalid_email_throws_a_user_creation_exception(): void
    {
        $email = 'invalid_email';
        $password = $this->faker->password();

        $this->expectException(UserCreationException::class);

        $this->createRandomUseCase->create($email, $password);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function test_a_new_user_with_the_specified_credentials_is_created(): void
    {
        $email = $this->faker->email();
        $password = $this->faker->password();

        $createRandomResult = $this->createRandomUseCase->create($email, $password);

        $hashedPassword = $this->database->getDoctrineConnection()->fetchOne(
            query: 'select password from users where email = :email',
            params: ['email' => $email],
        );

        $this->assertDatabaseCount('users', 2);
        $this->assertEquals($email, $createRandomResult->email);
        $this->assertTrue($this->hasher->check($password, $hashedPassword));
    }
}
