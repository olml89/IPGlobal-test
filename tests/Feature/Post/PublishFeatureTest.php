<?php declare(strict_types=1);

namespace Tests\Feature\Post;

use Database\Factories\ValueObjects\EmailFactory;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class PublishFeatureTest extends TestCase
{
    use PrepareDatabase;

    private readonly Connection $database;
    private readonly EntityManagerInterface $entityManager;
    private readonly User $user;
    private readonly Token $token;
    private readonly array $input;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->database = $this->app->get(Connection::class);
        $this->entityManager = $this->app->get(EntityManagerInterface::class);

        $this->setUpUser();
        $this->setUpToken();
        $this->setUpInput();
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

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setUpInput(): void
    {
        /** @var Faker $faker */
        $faker = $this->app->get(Faker::class);

        $this->input = [
            'title' => $faker->title(),
            'body' => $faker->text(),
        ];
    }

    private function getErrorMessage(TestResponse $response): string
    {
        return json_decode($response->getContent(), true)['message'];
    }

    public function test_request_without_api_token_header_generates_401_response(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api-Token header not set', $this->getErrorMessage($response));
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function test_unexisting_api_token_generates_401_response(): void
    {
        /**
         * Using this we don't have to implement the delete method in our token repository,
         * which is not needed by our domain, so we won't couple our domain to our tests.
         *
         * https://softwareengineering.stackexchange.com/questions/86844/is-it-ok-to-introduce-methods-that-are-used-only-during-unit-tests
         */
        $this->database->getDoctrineConnection()->delete(
            table: 'tokens',
            criteria: ['hash' => (string)$this->token->hash()],
        );

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Api-Token', (string)$this->token->hash())
            ->postJson('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api token not set or expired', $this->getErrorMessage($response));
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function test_expired_api_token_generates_401_response(): void
    {
        // We set the datetime expiration at now.
        // This is the right way to do it as the Token entity doesn't allow to modify it's internal expiredAt property.
        $this->database->getDoctrineConnection()->update(
            table: 'tokens',
            data: ['expires_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s')],
            criteria: ['hash' => (string)$this->token->hash()],
        );

        // We have to do this in order to avoid Doctrine using a cached version of the token when requested for it.
        $this->entityManager->clear();

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Api-Token', (string)$this->token->hash())
            ->postJson('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api token not set or expired', $this->getErrorMessage($response));
    }

    public function test_invalid_body_generates_422_response(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Api-Token', (string)$this->token->hash())
            ->postJson('/api/posts');

        $response->assertUnprocessable();
    }

    public function test_valid_body_generates_201_response(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Api-Token', (string)$this->token->hash())
            ->postJson('/api/posts', $this->input);

        $response->assertCreated();
        $response->assertHeader('Location');
        $this->assertEquals($this->user->id(), $response->json('user.id'));
    }
}
