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
use olml89\IPGlobalTest\User\Domain\User;

final class PublishUseCase
{
    public function __construct(
        private readonly UuidGenerator $uuidGenerator,
        private readonly PostRepository $postRepository,
        private readonly UserFactory $userFactory,
    ) {}

    /**
     * @throws PostCreationException | PostStorageException
     */
    public function publish(PublishData $publishData, User $user): PostResult
    {
        try {
            $post = new Post(
                id: Uuid::random($this->uuidGenerator),
                user: $user,
                title: new StringValueObject($publishData->title),
                body: new StringValueObject($publishData->body),
            );

            $this->postRepository->save($post);

            return new PostResult($post);
        }
        catch (ValueObjectException $valueObjectException) {
            throw new PostCreationException($valueObjectException->getMessage(), $valueObjectException);
        }
    }
}
