<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use olml89\IPGlobalTest\Common\Domain\RandomStringGenerator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\RandomStringGenerator as LaravelRandomStringGenerator;
use olml89\IPGlobalTest\Common\Infrastructure\Ramsey\UuidGenerator as RamseyUuidGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UuidGenerator::class,
            RamseyUuidGenerator::class
        );
        $this->app->bind(
            RandomStringGenerator::class,
            LaravelRandomStringGenerator::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
