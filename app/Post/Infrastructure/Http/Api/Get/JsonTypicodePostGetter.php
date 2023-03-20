<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Http\Api\Get;

use Database\Factories\ValueObjects\PasswordFactory;
use GuzzleHttp\Exception\GuzzleException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ApiConsumer;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Post as JsonPlaceholderTypicodePostData;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\User as JsonPlaceholderTypicodeUserData;
use olml89\IPGlobalTest\Common\Infrastructure\Ramsey\UuidGenerator;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\PostNotFoundException;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetriever;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCodeValidator;
use olml89\IPGlobalTest\User\Domain\Company;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\Url\Url;
use olml89\IPGlobalTest\User\Domain\Url\UrlValidator;
use olml89\IPGlobalTest\User\Domain\User;

/**
 * This service is not useful anymore since we have implemented persistence, and we can retrieve posts from
 * our own database, but I want to maintain this as a proof of example.
 *
 * But since the remote JsonPlaceholderTypicode api uses auto-incremental integers as identifiers it is not
 * compatible with our Domain entities anymore, so we have to override the remote values and
 * use random UUIDs instead.
 */
final class JsonTypicodePostGetter implements RemotePostRetriever
{
    public function __construct(
        private readonly ApiConsumer $jsonTypicodeApi,
        private readonly UuidGenerator $uuidGenerator,
        private readonly PasswordFactory $passwordFactory,
        private readonly EmailValidator $emailValidator,
        private readonly UrlValidator $urlValidator,
        private readonly ZipCodeValidator $zipCodeValidator,
    ) {}

    public function get(int $id): Post
    {
        try {
            $postResponse = $this->jsonTypicodeApi->getPost($id);
            $postData = JsonPlaceholderTypicodePostData::fromHttpResponse($postResponse);

            $userResponse = $this->jsonTypicodeApi->getUser($postData->userId);
            $userData = JsonPlaceholderTypicodeUserData::fromHttpResponse($userResponse);

            return new Post(
                // We omit the retrieved post id and create a random UUID instead
                id: Uuid::random($this->uuidGenerator),
                user: new User(
                    // We omit the retrieved user id and create a random UUID instead
                    id: Uuid::random($this->uuidGenerator),
                    // We generate a dummy password as JsonPlaceholderApi user resource doesn't expose one
                    password: $this->passwordFactory->random(),
                    name: new StringValueObject($userData->name),
                    username: new StringValueObject($userData->username),
                    email: new Email($userData->email, $this->emailValidator),
                    address: new Address(
                        street: $userData->address->street,
                        suite: $userData->address->suite,
                        city: $userData->address->city,
                        zipCode: new ZipCode($userData->address->zipcode, $this->zipCodeValidator),
                        geoLocation: new Geolocation(
                            latitude: $userData->address->geo->lat,
                            longitude: $userData->address->geo->lng,
                        ),
                    ),
                    phone: new StringValueObject($userData->phone),
                    website: new Url($userData->website, $this->urlValidator),
                    company: new Company(
                        name: $userData->company->name,
                        catchphrase: $userData->company->catchPhrase,
                        bs: $userData->company->bs,
                    ),
                ),
                title: new StringValueObject($postData->title),
                body: new StringValueObject($postData->body),
            );
        }
        catch (GuzzleException $e) {
            throw new PostNotFoundException((string)$id, $e);
        }
    }
}
