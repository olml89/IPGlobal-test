<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Infrastructure\Persistence;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use olml89\IPGlobalTest\User\Domain\UserStorageException;

final class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            new ClassMetadata(User::class),
        );
    }

    public function getByEmail(Email $email): ?User
    {
        return $this->getEntityManager()->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    /**
     * @throws UserStorageException
     */
    public function save(User $user): void
    {
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }
        catch (Exception $doctrineException) {
            throw new UserStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
