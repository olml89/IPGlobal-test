<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Infrastructure\Persistence;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\Security\Domain\TokenStorageException;
use olml89\IPGlobalTest\User\Domain\User;

final class DoctrineTokenRepository extends EntityRepository implements TokenRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct(
            $em,
            new ClassMetadata(Token::class),
        );
    }

    public function getByUser(User $user): ?Token
    {
        return $this
            ->getEntityManager()
            ->getRepository(Token::class)
            ->findOneBy(
                criteria: ['user' => $user],
                orderBy: ['expiresAt' => 'DESC'],
            );
    }

    public function getByHash(Md5Hash $hash): ?Token
    {
        return $this->getEntityManager()->getRepository(Token::class)->findOneBy(['hash' => $hash]);
    }

    /**
     * @throws TokenStorageException
     */
    public function save(Token $token): void
    {
        try {
            $this->getEntityManager()->persist($token);
            $this->getEntityManager()->flush();
        }
        catch (\Exception $doctrineException) {
            throw new TokenStorageException($doctrineException->getMessage(), $doctrineException);
        }
    }
}
