<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\EmailFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use Database\Factories\ValueObjects\UrlFactory;
use Database\Factories\ValueObjects\UuidFactory;
use Database\Factories\ValueObjects\ZipCodeFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\Company;
use olml89\IPGlobalTest\User\Domain\User;
use ReflectionException;

class UserFactory
{
    public function __construct(
        private readonly Faker $faker,
        private readonly UuidFactory $uuidFactory,
        private readonly EmailFactory $emailFactory,
        private readonly ZipCodeFactory $zipCodeFactory,
        private readonly UrlFactory $urlFactory,
        private readonly PasswordFactory $passwordFactory,
    ) {}

    /**
     * @throws ReflectionException
     */
    public function random(): User
    {
        return new User(
            id: $this->uuidFactory->random(),
            password: $this->passwordFactory->random(),
            name: new StringValueObject($this->faker->name()),
            username: new StringValueObject($this->faker->name()),
            email: $this->emailFactory->random(),
            address: new Address(
                street: $this->faker->streetAddress(),
                suite: $this->faker->name(),
                city: $this->faker->city(),
                zipCode: $this->zipCodeFactory->random(),
                geoLocation: new Geolocation(
                    latitude: $this->faker->latitude(),
                    longitude: $this->faker->longitude(),
                ),
            ),
            phone: new StringValueObject($this->faker->phoneNumber()),
            website: $this->urlFactory->random(),
            company: new Company(
                name: $this->faker->name,
                catchphrase: $this->faker->sentence(),
                bs: $this->faker->sentence(),
            ),
        );
    }

    /**
     * @throws ReflectionException
     */
    public function withCredentials(string $email, string $password): User
    {
        return $this
            ->random()
            ->setEmail($this->emailFactory->create($email))
            ->setPassword($this->passwordFactory->create($password));
    }
}
