<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain;

use JsonSerializable as JsonSerializableContract;

final class JsonSerializable implements JsonSerializableContract
{

    public function jsonSerialize(): array
    {
        return (array)$this;
    }
}
