<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Persistence;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Post\Domain\PostStorageException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;

final class DoctrinePostRepository extends EntityRepository implements PostRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(Post::class),
        );
    }

    public function get(Uuid $id): ?Post
    {
        return $this->getEntityManager()->getRepository(Post::class)->find($id);
    }

    /**
     * @throws PostStorageException
     */
    public function save(Post $post): void
    {
        try {
            $this->getEntityManager()->persist($post);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {
            throw new PostStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
