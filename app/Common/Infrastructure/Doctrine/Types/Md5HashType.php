<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;

final class Md5HashType extends ValidatedStringValueObjectType
{
    private const NAME = 'md5';

    protected function getChildTypeName(): string
    {
        return self::NAME;
    }

    protected function getChildClassName(): string
    {
        return Md5Hash::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'CHAR(32)';
    }
}
