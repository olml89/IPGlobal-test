<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Infrastructure\Console;

use Illuminate\Console\Command;
use olml89\IPGlobalTest\User\Application\Create\CreateData as CreateUserData;
use olml89\IPGlobalTest\User\Application\Create\CreateUseCase as CreateUser;
use olml89\IPGlobalTest\User\Domain\UserCreationException;

final class ArtisanCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
        {password}
        {name}
        {username}
        {email}
        {address_street}
        {address_suite}
        {address_city}
        {address_zipcode}
        {address_geo_lat}
        {address_geo_lng}
        {phone}
        {website}
        {company_name}
        {company_catchphrase}
        {company_bs}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user on the database.';

    private function getInputData(): CreateUserData
    {
        return new CreateUserData(
            password: $this->argument('password'),
            name: $this->argument('name'),
            username: $this->argument('username'),
            email: $this->argument('email'),
            address_street: $this->argument('address_street'),
            address_suite: $this->argument('address_suite'),
            address_city: $this->argument('address_city'),
            address_zipcode: $this->argument('address_zipcode'),
            address_geo_lat: (float)$this->argument('address_geo_lat'),
            address_geo_lng: (float)$this->argument('address_geo_lng'),
            phone: $this->argument('phone'),
            website: $this->argument('website'),
            company_name: $this->argument('company_name'),
            company_catchphrase: $this->argument('company_catchphrase'),
            company_bs: $this->argument('company_bs'),
        );
    }

    /**
     * Execute the console command.
     *
     * @throws UserCreationException | UserStorageException
     */
    public function handle(CreateUser $createUser): void
    {
        $createUserData = $this->getInputData();
        $result = $createUser->create($createUserData);

        $this->output->success('User created successfully');
        $this->output->write(json_encode($result, JSON_PRETTY_PRINT));
    }
}
