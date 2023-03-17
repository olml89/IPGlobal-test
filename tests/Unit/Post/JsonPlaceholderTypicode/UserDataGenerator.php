<?php declare(strict_types=1);

namespace Tests\Unit\Post\JsonPlaceholderTypicode;

use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Address as AddressData;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Company as CompanyData;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Geo as GeoData;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\User as UserData;
use Stringable;

final class UserDataGenerator implements Stringable
{
    private int $id;
    private string $name;
    private string $username;
    private string $email;
    private AddressData $addressData;
    private string $phone;
    private string $website;
    private CompanyData $companyData;
    private UserData $userData;

    public function __construct(Faker $faker)
    {
        $this->id = $faker->randomNumber();
        $this->name = $faker->name();
        $this->username = $faker->name();
        $this->email = $faker->email();
        $this->addressData = new AddressData(
            street: $faker->streetAddress(),
            suite: $faker->name(),
            city: $faker->city(),
            zipcode: $faker->postcode(),
            geo: new GeoData(
                lat: $faker->latitude(),
                lng: $faker->longitude(),
            ),
        );
        $this->phone = $faker->phoneNumber();
        // The response from JsonPlaceholderTypicode comes in this format
        $this->website = $faker->domainName();
        $this->companyData = new CompanyData(
            name: $faker->name(),
            catchPhrase: $faker->sentence(),
            bs: $faker->sentence(),
        );

        $this->setUserData();
    }

    private function setUserData(): void
    {
        $this->userData = new UserData(
            $this->id,
            $this->name,
            $this->username,
            $this->email,
            $this->addressData,
            $this->phone,
            $this->website,
            $this->companyData,
        );
    }

    public function withId(int $id): self
    {
        $this->id = $id;
        $this->setUserData();

        return $this;
    }

    public function __toString(): string
    {
        return json_encode($this->userData);
    }
}
