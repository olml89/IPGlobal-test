<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;

class ZipCodeType extends ValidatedStringValueObjectType
{
    private const NAME = 'zipcode';

    protected function getChildTypeName(): string
    {
        return self::NAME;
    }

    protected function getChildClassName(): string
    {
        return ZipCode::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(20)';
    }
}

