<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Get\JsonTypicodeGetPostGetter;
use Psr\Http\Message\ResponseInterface;

final class ApiConsumer
{
    private const URL = 'https://jsonplaceholder.typicode.com';

    public function __construct(
        private readonly Client $httpClient,
    ) {}

    /**
     * @throws GuzzleException
     */
    public function getPost(int $id): ResponseInterface
    {
        return $this->httpClient->get(self::URL.'/posts/'.$id);
    }

    /**
     * @throws GuzzleException
     */
    public function getUser(int $id): ResponseInterface
    {
        return $this->httpClient->get(self::URL.'/users/'.$id);
    }
}
