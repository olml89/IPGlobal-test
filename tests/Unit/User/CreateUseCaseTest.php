<?php declare(strict_types=1);

namespace Tests\Unit\User;

use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use olml89\IPGlobalTest\User\Application\Create\CreateData;
use olml89\IPGlobalTest\User\Application\Create\CreateUseCase;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\UserCreationException;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class CreateUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly Hasher $hasher;
    private readonly Connection $database;
    private readonly CreateUseCase $createUseCase;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->faker = $this->app->get(Faker::class);
        $this->hasher = $this->app->get(Hasher::class);
        $this->database = $this->app->get(Connection::class);
        $this->createUseCase = $this->app->get(CreateUseCase::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    private function prepareCreateData(
        string $password = null,
        string $email = null,
        string $zipCode = null,
        string $website = null,
    ): CreateData
    {
        return new CreateData(
            password: $password ?? $this->faker->password(),
            name: $this->faker->name(),
            username: $this->faker->userName(),
            email: $email ?? $this->faker->email(),
            address_street: $this->faker->streetAddress(),
            address_suite: $this->faker->name(),
            address_city: $this->faker->city(),
            address_zipcode: $zipCode ?? $this->faker->postcode(),
            address_geo_lat: $this->faker->latitude(),
            address_geo_lng: $this->faker->longitude(),
            phone: $this->faker->phoneNumber(),
            website: $website ?? $this->faker->url(),
            company_name: $this->faker->name(),
            company_catchphrase: $this->faker->sentence(),
            company_bs: $this->faker->sentence()
        );
    }

    public function test_invalid_email_throws_a_user_creation_exception(): void
    {
        $createData = $this->prepareCreateData(email: 'invalid_email');

        $this->expectException(UserCreationException::class);

        $this->createUseCase->create($createData);
    }

    public function test_invalid_zip_code_throws_a_user_creation_exception(): void
    {
        $createData = $this->prepareCreateData(zipCode: 'zip_code');

        $this->expectException(UserCreationException::class);

        $this->createUseCase->create($createData);
    }

    public function test_invalid_url_throws_a_user_creation_exception(): void
    {
        $createData = $this->prepareCreateData(website: 'invalid_url');

        $this->expectException(UserCreationException::class);

        $this->createUseCase->create($createData);
    }

    public function test_a_new_user_with_the_specified_credentials_is_created(): void
    {
        $email = $this->faker->email();
        $password = $this->faker->password();
        $createData = $this->prepareCreateData(password: $password, email: $email);

        $createResult = $this->createUseCase->create($createData);

        $hashedPassword = $this->database->getDoctrineConnection()->fetchOne(
            query: 'select password from users where email = :email',
            params: ['email' => $email],
        );

        $this->assertDatabaseCount('users', 2);
        $this->assertEquals($email, $createResult->email);
        $this->assertTrue($this->hasher->check($password, $hashedPassword));
    }
}
