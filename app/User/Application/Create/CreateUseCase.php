<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Application\Create;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;
use olml89\IPGlobalTest\User\Application\UserResult;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCodeValidator;
use olml89\IPGlobalTest\User\Domain\Company;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\Password\Password;
use olml89\IPGlobalTest\User\Domain\Url\Url;
use olml89\IPGlobalTest\User\Domain\Url\UrlValidator;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserCreationException;
use olml89\IPGlobalTest\User\Domain\UserRepository;

final class CreateUseCase
{
    public function __construct(
        private readonly Hasher $hasher,
        private readonly UserRepository $userRepository,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EmailValidator $emailValidator,
        private readonly UrlValidator $urlValidator,
        private readonly ZipCodeValidator $zipCodeValidator,
    ) {}

    /**
     * @throws ValueObjectException
     */
    private function createUser(CreateData $createData): User
    {
        return new User(
            id: Uuid::random($this->uuidGenerator),
            password: Password::create($createData->password, $this->hasher),
            name: new StringValueObject($createData->name),
            username: new StringValueObject($createData->username),
            email: new Email($createData->email, $this->emailValidator),
            address: new Address(
                street: $createData->address_street,
                suite: $createData->address_suite,
                city: $createData->address_city,
                zipCode: new ZipCode($createData->address_zipcode, $this->zipCodeValidator),
                geoLocation: new Geolocation(
                    latitude: $createData->address_geo_lat,
                    longitude: $createData->address_geo_lng,
                ),
            ),
            phone: new StringValueObject($createData->phone),
            website: new Url($createData->website, $this->urlValidator),
            company: new Company(
                name: $createData->name,
                catchphrase: $createData->company_catchphrase,
                bs: $createData->company_bs,
            ),
        );
    }

    /**
     * @throws UserCreationException | UserStorageException
     */
    public function create(CreateData $createData): UserResult
    {
        try {
            $user = $this->createUser($createData);
            $this->userRepository->save($user);

            return new UserResult($user);
        }
        catch (ValueObjectException $valueObjectException) {
            throw new UserCreationException($valueObjectException->getMessage(), $valueObjectException);
        }
    }
}
