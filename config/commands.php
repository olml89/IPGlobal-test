<?php declare(strict_types=1);

use olml89\IPGlobalTest\User\Infrastructure\Console\ArtisanCreateCommand as ArtisanCreateUserCommand;
use olml89\IPGlobalTest\User\Infrastructure\Console\ArtisanCreateRandomCommand as ArtisanCreateRandomUserCommand;

return [
    ArtisanCreateUserCommand::class,
    ArtisanCreateRandomUserCommand::class,
];
