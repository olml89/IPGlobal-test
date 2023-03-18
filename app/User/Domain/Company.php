<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;

final class Company extends JsonSerializableObject
{
    public function __construct(
        public readonly string $name,
        public readonly string $catchphrase,
        public readonly string $bs,
    ) {}
}
