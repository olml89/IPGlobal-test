<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Url;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUrlException extends ValueObjectException
{
    public function __construct(string $url)
    {
        parent::__construct(
            sprintf('Value must be a valid URL, \'%s\' provided', $url)
        );
    }
}
