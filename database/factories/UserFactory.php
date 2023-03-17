<?php declare(strict_types=1);

namespace Database\Factories;

use Database\Factories\ValueObjects\EmailFactory;
use Database\Factories\ValueObjects\UrlFactory;
use Database\Factories\ValueObjects\ZipCodeFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\Url;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Address\Company;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\User;
use ReflectionException;

class UserFactory
{
    public function __construct(
        private readonly Faker $faker,
        private readonly EmailFactory $emailFactory,
        private readonly ZipCodeFactory $zipCodeFactory,
        private readonly UrlFactory $urlFactory,
    ) {}

    /**
     * @throws ReflectionException
     */
    public function createWithId(int $id): User
    {
        return new User(
            id: new AutoIncrementalId($id),
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
