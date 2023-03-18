<?php declare(strict_types=1);

use olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Migrations\DiffCommand;
use olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Migrations\MigrateCommand;
use olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types\AutoIncrementalIdType;
use olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types\UuidType;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Post\Infrastructure\Persistence\DoctrinePostRepository;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use olml89\IPGlobalTest\User\Infrastructure\Persistence\DoctrineUserRepository;

return [

    'connection' => [
        'driver' => env('DOCTRINE_DRIVER', 'pdo_mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'dbname' => env('DB_DATABASE', 'laravel'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    'mappings' => [
        app_path('Post/Infrastructure/Persistence'),
        app_path('User/Infrastructure/Persistence'),
    ],

    'proxies' => [
        'namespace' => false,
        'path' => storage_path('proxies'),
        'auto_generate' => config('app.debug', false)
    ],

    'events' => [
        'listeners' => [],
        'subscribers' => [],
    ],

    'filters' => [],

    'custom_types' => [
        AutoIncrementalIdType::class,
        UuidType::class,
    ],

    'repositories' => [
        PostRepository::class => DoctrinePostRepository::class,
        UserRepository::class => DoctrineUserRepository::class,
    ],

    'migrations' => [

        'schema' => [
            'filter' => '/^(?!password_resets|failed_jobs).*$/',
        ],

        'default' => [

            'table_storage' => [
                'table_name' => 'doctrine_migrations',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'Database\\Migrations' => database_path('doctrine-migrations'),
            ],

            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',

        ],

        'commands' => [
            DiffCommand::class,
            MigrateCommand::class,
        ],

    ],

];
