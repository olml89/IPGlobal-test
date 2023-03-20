<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Domain\Md5Hash;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class Md5Hash extends StringValueObject
{
    private function __construct(string $hash)
    {
        parent::__construct($hash);
    }

    private static function ensureIsAValidMd5Hash($hash): void
    {
        if (strlen($hash) !== 32) {
            throw new InvalidMd5HashException($hash);
        }
    }

    public static function fromHash(string $hash): self
    {
        self::ensureIsAValidMd5Hash($hash);

        return new self($hash);
    }

    public static function fromPlain(string $plain): self
    {
        return new self(
            md5($plain),
        );
    }
}
