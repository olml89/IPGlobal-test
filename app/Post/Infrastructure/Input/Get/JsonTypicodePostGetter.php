<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Input\Get;

use GuzzleHttp\Exception\GuzzleException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\Url;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\UrlValidator;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ApiConsumer;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Post as JsonPlaceholderTypicodePostData;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\User as JsonPlaceholderTypicodeUserData;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetriever;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetrievingException;
use olml89\IPGlobalTest\User\Domain\Address\Address;
use olml89\IPGlobalTest\User\Domain\Address\Company;
use olml89\IPGlobalTest\User\Domain\Address\Geolocation\Geolocation;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCode;
use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCodeValidator;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;
use olml89\IPGlobalTest\User\Domain\User;

final class JsonTypicodePostGetter implements RemotePostRetriever
{
    public function __construct(
        private readonly ApiConsumer $jsonTypicodeApi,
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
                id: new AutoIncrementalId($postData->id),
                user: new User(
                    id: new AutoIncrementalId($userData->id),
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
            throw new RemotePostRetrievingException($id, $e);
        }
    }
}
