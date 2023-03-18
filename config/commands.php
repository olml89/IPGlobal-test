<?php declare(strict_types=1);

use olml89\IPGlobalTest\User\Infrastructure\Input\Create\ArtisanCreateCommand as ArtisanCreateUserCommand;
use olml89\IPGlobalTest\User\Infrastructure\Input\Create\ArtisanCreateRandomCommand as ArtisanCreateRandomUserCommand;

return [
    ArtisanCreateUserCommand::class,
    ArtisanCreateRandomUserCommand::class,
];
