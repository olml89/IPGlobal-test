<?php declare(strict_types=1);

namespace Tests\Feature\Security;

use Database\Factories\UserFactory;
use Database\Factories\ValueObjects\EmailFactory;
use Faker\Generator as Faker;
use Illuminate\Testing\TestResponse;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class AuthenticateUseCaseFeatureTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly UserFactory $userFactory;
    private readonly EmailFactory $emailFactory;
    private readonly UserRepository $userRepository;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->faker = $this->app->get(Faker::class);
        $this->userFactory = $this->app->get(UserFactory::class);
        $this->emailFactory = $this->app->get(EmailFactory::class);
        $this->userRepository = $this->app->get(UserRepository::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    public function test_invalid_email_generates_422_response(): void
    {
        $input = [
            'email' => 'invalid_mail',
            'password' => $this->faker->password(),
        ];

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertUnprocessable();
    }

    private function getErrorMessage(TestResponse $response): string
    {
        return json_decode($response->getContent(), true)['message'];
    }

    public function test_unexisting_user_generates_404_response(): void
    {
        $notRegisteredEmail = 'not-registered-email@fake-mail.com';
        $input = [
            'email' => $notRegisteredEmail,
            'password' => $this->faker->password(),
        ];

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->postJson('/api/auth', $input);

        $response->assertNotFound();

        $this->assertEquals(
            sprintf('User with email \'%s\' does not exist', $notRegisteredEmail),
            $this->getErrorMessage($response)
        );
    }

    public function test_incorrect_password_generates_404_response(): void
    {
        $input = [
            'email' => 'johndeere@fake-mail.com',
            'password' => '54321',
        ];

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
        $user = $this->userRepository->getByEmail(
            $this->emailFactory->create('johndeere@fake-mail.com'),
        );

        $input = [
            'email' => (string)$user->email(),
            'password' => '12345',
        ];

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
