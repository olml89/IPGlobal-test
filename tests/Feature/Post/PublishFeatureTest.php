<?php declare(strict_types=1);

namespace Tests\Feature\Post;

use Database\Factories\UserFactory;
use DateTimeImmutable;
use Faker\Generator as Faker;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\User\Domain\User;
use ReflectionClass;
use Tests\TestCase;

final class PublishFeatureTest extends TestCase
{
    private readonly User $user;
    private readonly string $hash;
    private readonly array $input;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var Faker $faker */
        $faker = $this->app->get(Faker::class);

        /** @var UserFactory $userFactory */
        $userFactory = $this->app->get(UserFactory::class);

        $this->user = $userFactory->create();
        $this->hash = $faker->md5();

        $this->input = [
            'title' => $faker->title(),
            'body' => $faker->text(),
        ];
    }

    private function setUpToken(?Token $token): void
    {
        $this->mock(TokenRepository::class, function(MockInterface $mock) use($token): void {
            $mock->shouldReceive('getByHash')
                ->once()
                ->andReturn($token);
        });
    }

    private function getErrorMessage(TestResponse $response): string
    {
        return json_decode($response->getContent(), true)['message'];
    }

    public function test_request_without_api_token_header_generates_401_response(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api-Token header not set', $this->getErrorMessage($response));
    }

    public function test_unexisting_api_token_generates_401_response(): void
    {
        $this->setUpToken(null);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Api-Token', $this->hash)
            ->post('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api token not set or expired', $this->getErrorMessage($response));
    }

    private function expireToken(): void
    {
        $expiredToken = $this->mock(Token::class, function(MockInterface $mock): void {
            $mock->shouldReceive('isExpired')
                ->once()
                ->andReturnTrue();
        });

        $this->mock(TokenRepository::class, function(MockInterface $mock) use($expiredToken): void {
            $mock->shouldReceive('getByHash')
                ->once()
                ->andReturn($expiredToken);
        });
    }

    public function test_expired_api_token_generates_401_response(): void
    {
        $this->expireToken();

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Api-Token', $this->hash)
            ->post('/api/posts', $this->input);

        $response->assertUnauthorized();
        $this->assertEquals('Api token not set or expired', $this->getErrorMessage($response));
    }

    public function test_valid_token_and_generates_201_response(): void
    {
        $this->setUpToken(
            new Token($this->user, Md5Hash::fromHash($this->hash))
        );

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Api-Token', $this->hash)
            ->post('/api/posts', $this->input);

        $response->assertCreated();
        $response->assertHeader('Location');
        $this->assertEquals($this->user->id(), $response->json('user.id'));
    }
}
