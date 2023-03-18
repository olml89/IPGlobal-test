<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Application;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Company;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Url\Url;
use olml89\IPGlobalTest\User\Domain\User;

final class UserResult extends JsonSerializableObject
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $username;
    public readonly Email $email;
    public readonly Address $address;
    public readonly string $phone;
    public readonly Url $website;
    public readonly Company $company;

    public function __construct(User $user)
    {
        $this->id = (string)$user->id();
        $this->name = (string)$user->name();
        $this->username = (string)$user->username();
        $this->email = $user->email();
        $this->address = $user->address();
        $this->phone = (string)$user->phone();
        $this->website = $user->website();
        $this->company = $user->company();
    }
}
