<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;

final class Address extends JsonSerializableObject
{
    public function __construct(
        public readonly string $street,
        public readonly string $suite,
        public readonly string $city,
        public readonly ZipCode $zipCode,
        public readonly Geolocation $geoLocation,
    ) {}
}
