<?php declare(strict_types=1);

namespace Tests\Feature\Security;

use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\Password\Password;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\TestCase;

final class AuthenticateUseCaseFeatureTest extends TestCase
{
    private readonly Faker $faker;
    private readonly UserFactory $userFactory;
    private readonly Hasher $hasher;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
        $this->userFactory = $this->app->get(UserFactory::class);
        $this->hasher = $this->app->get(Hasher::class);
    }

    private function prepareInput(?string $email = null, ?string $password = null): array
    {
        return [
            'email' => $email ?? $this->faker->email(),
            'password' => $password ?? $this->faker->password(),
        ];
    }

    public function test_invalid_email_generates_422_response(): void
    {
        $input = $this->prepareInput(email: 'invalid email');

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertUnprocessable();
    }

    private function prepareUser(?User $user): void
    {
        $this->mock(UserRepository::class, function(MockInterface $mock) use($user): void {
            $mock->shouldReceive('getByEmail')
                ->once()
                ->andReturn($user);
        });
    }

    private function getErrorMessage(TestResponse $response): string
    {
        return json_decode($response->getContent(), true)['message'];
    }

    /**
     * @throws \ReflectionException
     */
    public function test_unexisting_user_generates_404_response(): void
    {
        $password = $this->faker->password();
        $user = $this->userFactory->create()->setPassword(Password::create($password, $this->hasher));
        $this->prepareUser(null);
        $input = $this->prepareInput(email: (string)$user->email(), password: $password);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertNotFound();

        $this->assertEquals(
            sprintf('User with email \'%s\' does not exist', $user->email()),
            $this->getErrorMessage($response)
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function test_incorrect_password_generates_404_response(): void
    {
        $user = $this->userFactory->create()->setPassword(Password::create('12345', $this->hasher));
        $this->prepareUser($user);
        $input = $this->prepareInput(email: (string)$user->email(), password: '54321');

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertNotFound();

        $this->assertEquals(
            'The provided password is invalid',
            $this->getErrorMessage($response)
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function test_existing_user_generates_a_valid_token(): void
    {
        $password = $this->faker->password();
        $user = $this->userFactory->create()->setPassword(Password::create($password, $this->hasher));
        $this->prepareUser($user);
        $input = $this->prepareInput(email: (string)$user->email(), password: $password);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertOk();

        $this->assertEquals(
            (string)$user->id(),
            $response->json('user_id')
        );
    }
}
