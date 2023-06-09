<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;

class UuidType extends ValidatedStringValueObjectType
{
    private const NAME = 'uuid';

    protected function getChildTypeName(): string
    {
        return self::NAME;
    }

    protected function getChildClassName(): string
    {
        return Uuid::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}
