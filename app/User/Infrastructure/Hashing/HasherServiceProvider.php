<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Infrastructure\Hashing;

use Illuminate\Contracts\Hashing\Hasher as LaravelHashingContract;
use Illuminate\Foundation\Application;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\ServiceProvider;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Infrastructure\Hashing\Hasher as LaravelHasher;

final class HasherServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(Hasher::class, function(Application $app): Hasher {

            /** @var HashManager $hashManager */
            $hashManager = $app->get(HashManager::class);

            /** @var LaravelHashingContract $laravelHashingContract */
            $laravelHashingContract = $hashManager->driver();

            return new LaravelHasher($laravelHashingContract);
        });
    }
}
