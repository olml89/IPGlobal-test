<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class Uuid extends StringValueObject
{
    private function __construct(string $uuid)
    {
        parent::__construct($uuid);
    }

    /**
     * @throws InvalidUuidException
     */
    public static function create(string $uuid, UuidValidator $uuidValidator): self
    {
        self::ensureIsAValidUuid($uuid, $uuidValidator);

        return new self($uuid);
    }

    public static function random(UuidGenerator $uuidGenerator): self
    {
        return new self($uuidGenerator->random());
    }

    private static function ensureIsAValidUuid(string $uuid, UuidValidator $uuidValidator): void
    {
        if (!$uuidValidator->isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }
    }
}
