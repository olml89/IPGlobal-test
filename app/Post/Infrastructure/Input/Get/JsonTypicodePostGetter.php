<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Input\Get;

use GuzzleHttp\Exception\GuzzleException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ApiConsumer;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetriever;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetrievingException;

final class JsonTypicodePostGetter implements RemotePostRetriever
{
    public function __construct(
        private readonly ApiConsumer $jsonTypicodeApi,
    ) {}

    public function get(int $id): Post
    {
        try {
            $response = $this->jsonTypicodeApi->getPost($id);
            $postData = JsonTypicodeGetPostResponseData::fromHttpResponse($response);

            return new Post(
                id: new AutoIncrementalId($postData->id),
                userId: new AutoIncrementalId($postData->userId),
                title: new StringValueObject($postData->title),
                body: new StringValueObject($postData->body),
            );
        }
        catch (GuzzleException $e) {
            throw new RemotePostRetrievingException($id, $e);
        }
    }
}
