<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid;

interface UuidValidator
{
    public function isValid(string $uuid): bool;
}
