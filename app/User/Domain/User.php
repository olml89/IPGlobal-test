<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\Url;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Email\Email;

class User
{
    public function __construct(
        // The id can't be set as readonly here because of a known issue of Doctrine process of hydrating foreign
        // keys through proxy lazy loading when they are a composite value (not a string or an integer),
        // see: \Doctrine\ORM\Mapping\ReflectionReadonlyProperty on line 45.
        // the correct solution would be to do a pull request to try to fix the issue and linking our
        // composer doctrine version to our own fork until they approve it.
        private Uuid $id,
        private StringValueObject $name,
        private StringValueObject $username,
        private Email $email,
        private Address $address,
        private StringValueObject $phone,
        private Url $website,
        private Company $company,
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function setName(StringValueObject $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function username(): StringValueObject
    {
        return $this->username;
    }

    public function setUsername(StringValueObject $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function phone(): StringValueObject
    {
        return $this->phone;
    }

    public function setPhone(StringValueObject $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function website(): Url
    {
        return $this->website;
    }

    public function setWebsite(Url $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
