<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\Url;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Email\Email;

final class User
{
    public function __construct(
        private readonly Uuid $id,
        private readonly StringValueObject $name,
        private readonly StringValueObject $username,
        private readonly Email $email,
        private readonly Address $address,
        private readonly StringValueObject $phone,
        private readonly Url $website,
        private readonly Company $company,
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function username(): StringValueObject
    {
        return $this->username;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function phone(): StringValueObject
    {
        return $this->phone;
    }

    public function website(): Url
    {
        return $this->website;
    }

    public function company(): Company
    {
        return $this->company;
    }
}
