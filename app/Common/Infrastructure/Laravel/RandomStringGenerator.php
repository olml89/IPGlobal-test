<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel;

use Illuminate\Support\Str;
use olml89\IPGlobalTest\Common\Domain\RandomStringGenerator as LaravelRandomStringGenerator;

final class RandomStringGenerator implements LaravelRandomStringGenerator
{
    public function generate(int $length = null): string
    {
        return Str::random($length);
    }
}
