<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Application\Create;

final class CreateData
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly string $address_street,
        public readonly string $address_suite,
        public readonly string $address_city,
        public readonly string $address_zipcode,
        public readonly float $address_geo_lat,
        public readonly float $address_geo_lng,
        public readonly string $phone,
        public readonly string $website,
        public readonly string $company_name,
        public readonly string $company_catchphrase,
        public readonly string $company_bs,
    ) {}
}
