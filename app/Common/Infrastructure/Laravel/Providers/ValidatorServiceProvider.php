<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Email\EmailValidator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\IntBiggerThan0Validator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation\EmailValidator as LaravelEmailValidator;

class ValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EmailValidator::class,
            LaravelEmailValidator::class
        );
    }
}
