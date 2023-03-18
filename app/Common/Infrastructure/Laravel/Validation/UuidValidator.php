<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidValidator as UuidValidatorContract;

final class UuidValidator extends Validator implements UuidValidatorContract
{
    public function isValid(string $uuid): bool
    {
        return $this->factory->make([$uuid], ['uuid'])->passes();
    }
}
