<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Url;

interface UrlValidator
{
    public function isValid(string $url): bool;
}
