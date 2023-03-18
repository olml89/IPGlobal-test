<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Url;

interface UrlValidator
{
    public function isValid(string $url): bool;
}
