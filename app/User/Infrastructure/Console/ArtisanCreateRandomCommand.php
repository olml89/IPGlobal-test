<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\IPGlobalTest\User\Application\Create\CreateRandomUseCase as CreateRandom;
use olml89\IPGlobalTest\User\Domain\UserCreationException;
use olml89\IPGlobalTest\User\Domain\UserStorageException;


final class ArtisanCreateRandomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create:random {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user with random properties (except the password) on the database.';

    /**
     * Execute the console command.
     *
     * @throws UserCreationException | UserStorageException
     */
    public function handle(CreateRandom $createUser): void
    {
        $result = $createUser->create(
            email: $this->argument('email'),
            password: $this->argument('password'),
        );

        $this->output->success('User created successfully');
        $this->output->write(json_encode($result, JSON_PRETTY_PRINT));
    }
}
