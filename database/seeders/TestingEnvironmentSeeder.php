<?php declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;

final class TestingEnvironmentSeeder extends Seeder
{
    private const GENESIS_USER_EMAIL = 'johndeere@fake-mail.com';
    private const GENESIS_USER_PASSWORD = '12345';

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function run(): void
    {
        /**
         * Create a user with the following credentials:
         *      email:      johndeere@fake-mail.com
         *      password:   12345
         */
        $user = $this->createGenesisUser(self::GENESIS_USER_EMAIL, self::GENESIS_USER_PASSWORD);

        /**
         * Create a valid token for the user
         */
        $this->createApiToken($user);

        /**
         * Create a random post for the user
         */
        $this->createRandomPost($user);
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \ReflectionException
     */
    private function createGenesisUser(string $email, string $password): User
    {
        /** @var UserFactory $userFactory */
        $userFactory = $this->container->get(UserFactory::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get(UserRepository::class);

        $user = $userFactory->withCredentials($email, $password);
        $userRepository->save($user);

        return $user;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function createApiToken(User $user): void
    {
        /** @var TokenRepository $tokenRepository */
        $tokenRepository = $this->container->get(TokenRepository::class);

        /** @var Faker $faker */
        $faker = $this->container->get(Faker::class);

        $token = new Token($user, Md5Hash::fromHash($faker->md5()));
        $tokenRepository->save($token);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    private function createRandomPost(User $user): void
    {
        /** @var PostFactory $postFactory */
        $postFactory = $this->container->get(PostFactory::class);

        /** @var PostRepository $postRepository */
        $postRepository = $this->container->get(PostRepository::class);

        $post = $postFactory->withUser($user);
        $postRepository->save($post);
    }
}
