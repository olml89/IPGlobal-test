<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Ramsey;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator as UuidGeneratorContract;
use Ramsey\Uuid\UuidFactory;

final class UuidGenerator implements UuidGeneratorContract
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
    ) {}

    public function random(): string
    {
        return (string)$this->uuidFactory->uuid4();
    }
}
