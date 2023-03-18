<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\IntBiggerThan0Validator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\UrlValidator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidValidator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation\EmailValidator as LaravelEmailValidator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation\UrlValidator as LaravelUrlValidator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation\UuidValidator as LaravelUuidValidator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation\ZipCodeValidator as LaravelZipCodeValidator;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCodeValidator;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;

class ValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EmailValidator::class,
            LaravelEmailValidator::class
        );
        $this->app->bind(
            UrlValidator::class,
            LaravelUrlValidator::class
        );
        $this->app->bind(
            ZipCodeValidator::class,
            LaravelZipCodeValidator::class
        );
        $this->app->bind(
            UuidValidator::class,
            LaravelUuidValidator::class
        );
    }
}
