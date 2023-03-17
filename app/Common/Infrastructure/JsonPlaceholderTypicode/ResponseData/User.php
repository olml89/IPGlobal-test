<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData;

use Psr\Http\Message\ResponseInterface;

final class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly Address $address,
        public readonly string $phone,
        public readonly string $website,
        public readonly Company $company,
    ) {}

    public static function fromHttpResponse(ResponseInterface $response): self
    {
        $data = json_decode($response->getBody()->getContents(), true);

        return new self(
            id: $data['id'],
            name: $data['name'],
            username: $data['username'],
            email: $data['email'],
            address: new Address(
                street: $data['address']['street'],
                suite: $data['address']['suite'],
                city: $data['address']['city'],
                zipcode: $data['address']['zipcode'],
                geo: new Geo(
                    lat: (float)$data['address']['geo']['lat'],
                    lng: (float)$data['address']['geo']['lng'],
                ),
            ),
            phone: $data['phone'],
            // website from response from JsonPlaceholderTypicode comes in this format (a domain only)
            website: 'https://'.$data['website'],
            company: new Company(
                name: $data['company']['name'],
                catchPhrase: $data['company']['catchPhrase'],
                bs: $data['company']['bs'],
            ),
        );
    }
}
