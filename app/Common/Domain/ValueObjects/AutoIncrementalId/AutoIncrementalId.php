<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId;

use olml89\IPGlobalTest\Common\Domain\Validation\IntBiggerThan0;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\IntValueObject;

final class AutoIncrementalId extends IntValueObject
{
    public function __construct(int $id)
    {
        $this->ensureIsBiggerThan0($id);

        parent::__construct($id);
    }

    private function ensureIsBiggerThan0(int $id): void
    {
        $rule = new IntBiggerThan0($id);

        if (!$rule->check()) {
            throw new InvalidAutoIncrementalIdException($rule->failureMessage());
        }
    }
}
