<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\EmailFactory;
use Database\Factories\ValueObjects\UrlFactory;
use Database\Factories\ValueObjects\UuidFactory;
use Database\Factories\ValueObjects\ZipCodeFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
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
    ) {}

    /**
     * @throws ReflectionException
     */
    public function create(): User
    {
        return new User(
            id: $this->uuidFactory->create(),
            name: new StringValueObject($this->faker->name()),
            username: new StringValueObject($this->faker->name()),
            email: $this->emailFactory->create(),
            address: new Address(
                street: $this->faker->streetAddress(),
                suite: $this->faker->name(),
                city: $this->faker->city(),
                zipCode: $this->zipCodeFactory->create(),
                geoLocation: new Geolocation(
                    latitude: $this->faker->latitude(),
                    longitude: $this->faker->longitude(),
                ),
            ),
            phone: new StringValueObject($this->faker->phoneNumber()),
            website: $this->urlFactory->create(),
            company: new Company(
                name: $this->faker->name,
                catchphrase: $this->faker->sentence(),
                bs: $this->faker->sentence(),
            ),
        );
    }
}
