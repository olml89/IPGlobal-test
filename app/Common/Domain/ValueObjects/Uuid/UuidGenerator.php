<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid;

interface UuidGenerator
{
    public function random(): string;
}
