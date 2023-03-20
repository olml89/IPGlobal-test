<?php declare(strict_types=1);

namespace Tests\Unit\Security;

use Database\Factories\ValueObjects\EmailFactory;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use olml89\IPGlobalTest\Security\Application\AuthenticateUseCase;
use olml89\IPGlobalTest\Security\Application\AuthenticationData;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\User\Domain\Email\InvalidEmailException;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserNotFoundException;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class AuthenticateUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly Connection $database;
    private readonly EntityManagerInterface $entityManager;
    private readonly AuthenticateUseCase $authenticateUseCase;
    private readonly User $user;
    private readonly Token $token;

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
        $this->database = $this->app->get(Connection::class);
        $this->entityManager = $this->app->get(EntityManagerInterface::class);
        $this->authenticateUseCase = $this->app->get(AuthenticateUseCase::class);

        $this->setUpUser();
        $this->setUpToken();
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \ReflectionException
     */
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

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setUpToken(): void
    {
        /** @var TokenRepository $tokenRepository */
        $tokenRepository = $this->app->get(TokenRepository::class);

        $this->token = $tokenRepository->getByUser($this->user);
    }

    public function test_invalid_email_throws_invalid_email_exception(): void
    {
        $authenticationData = new AuthenticationData(
            email: 'invalid_email',
            password: $this->faker->password()
        );

        $this->expectException(InvalidEmailException::class);

        $this->authenticateUseCase->authenticate($authenticationData);
    }

    public function test_unexisting_email_throws_user_not_found_exception(): void
    {
        $authenticationData = new AuthenticationData(
            email: $this->faker->email(),
            password: $this->faker->password()
        );

        $this->expectException(UserNotFoundException::class);

        $this->authenticateUseCase->authenticate($authenticationData);
    }

    public function test_invalid_password_throws_user_not_found_exception(): void
    {
        $authenticationData = new AuthenticationData(
            email: (string)$this->user->email(),
            password: $this->faker->password()
        );

        $this->expectException(UserNotFoundException::class);

        $this->authenticateUseCase->authenticate($authenticationData);
    }

    public function test_non_expired_token_is_returned(): void
    {
        $authenticationData = new AuthenticationData(
            email: (string)$this->user->email(),
            password: '12345',
        );

        $authenticationResult = $this->authenticateUseCase->authenticate($authenticationData);

        $this->assertEquals($this->token->hash(), $authenticationResult->token);
        $this->assertEquals(
            $this->token->expiresAt()->format('c'),
            $authenticationResult->expires_at
        );
    }

    /**
     * @throws \Exception
     */
    public function test_expired_token_is_replaced_by_a_new_valid_token(): void
    {
        $this->database->getDoctrineConnection()->update(
            table: 'tokens',
            data: ['expires_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s')],
            criteria: ['hash' => (string)$this->token->hash()],
        );
        $this->entityManager->clear();

        $authenticationData = new AuthenticationData(
            email: (string)$this->user->email(),
            password: '12345',
        );

        $authenticationResult = $this->authenticateUseCase->authenticate($authenticationData);

        $this->assertDatabaseCount('tokens', 2);
        $this->assertNotEquals($this->token->hash(), $authenticationResult->token);
        $this->assertEquals(
            (new DateTimeImmutable())->modify('+1 hour')->format('c'),
            (new DateTimeImmutable($authenticationResult->expires_at))->format('c')
        );
    }
}
