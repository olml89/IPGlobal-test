<?php declare(strict_types=1);

namespace Tests;

trait PrepareDatabase
{
    public function migrate(): void
    {
        $this->artisan('doctrine:migrations:migrate');
        $this->artisan('db:seed TestingEnvironmentSeeder');
    }

    public function resetMigrations(): void
    {
        $this->artisan('doctrine:migrations:reset');
    }
}
