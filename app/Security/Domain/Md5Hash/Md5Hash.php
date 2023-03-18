<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Domain\Md5Hash;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class Md5Hash extends StringValueObject
{
    public function __construct(string $value)
    {
        parent::__construct(md5($value));
    }

    public function equals(string $value): bool
    {
        return md5($value) === (string)$this;
    }
}
