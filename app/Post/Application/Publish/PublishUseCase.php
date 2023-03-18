<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Publish;

use Database\Factories\UserFactory;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidGenerator;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;
use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\PostCreationException;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Post\Domain\PostStorageException;
use ReflectionException;

final class PublishUseCase
{
    public function __construct(
        private readonly UuidGenerator $uuidGenerator,
        private readonly PostRepository $postRepository,
        private readonly UserFactory $userFactory,
    ) {}

    /**
     * @throws ReflectionException | ValueObjectException
     */
    private function createPost(PublishData $publishData): Post
    {
        // We create an authentic UUID as an id, but we simulate the retrieval of the current user
        // of the session or the API token
        return new Post(
            id: Uuid::random($this->uuidGenerator),
            user: $this->userFactory->create(),
            title: new StringValueObject($publishData->title),
            body: new StringValueObject($publishData->body),
        );
    }

    /**
     * @throws PostCreationException | PostStorageException
     */
    public function publish(PublishData $publishData): PostResult
    {
        try {
            $post = $this->createPost($publishData);
            $this->postRepository->save($post);

            return new PostResult($post);
        }
        catch (ReflectionException|ValueObjectException $valueObjectException) {
            throw new PostCreationException($valueObjectException->getMessage(), $valueObjectException);
        }
    }
}
