<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\Url;

class UrlType extends ValidatedStringValueObjectType
{
    private const NAME = 'url';

    protected function getChildTypeName(): string
    {
        return self::NAME;
    }

    protected function getChildClassName(): string
    {
        return Url::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(2048)';
    }
}

