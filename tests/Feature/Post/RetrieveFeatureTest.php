<?php declare(strict_types=1);

namespace Test\Feature\Post;

use Database\Factories\ValueObjects\EmailFactory;
use Database\Factories\ValueObjects\UuidFactory;
use Doctrine\ORM\EntityManagerInterface;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\User\Domain\User;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class RetrieveFeatureTest extends TestCase
{
    use PrepareDatabase;

    private readonly UuidFactory $uuidFactory;
    private readonly EntityManagerInterface $entityManager;
    private User $user;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->uuidFactory = $this->app->get(UuidFactory::class);
        $this->entityManager = $this->app->get(EntityManagerInterface::class);

        $this->setUpUser();
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \ReflectionException
     */
    private function setUpUser(): void
    {
        /** @var EmailFactory $emailFactory */
        $emailFactory = $this->app->get(EmailFactory::class);

        /** @var UserRepository $userRepository */
        $userRepository = $this->app->get(UserRepository::class);

        $this->user = $userRepository->getByEmail(
            $emailFactory->create('johndeere@fake-mail.com')
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function test_invalid_id_generates_422_response(): void
    {
        $invalidUuid = $this->uuidFactory->create('invalid_uuid');

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->get('/api/posts/'.$invalidUuid);

        $response->assertUnprocessable();
    }

    /**
     * @throws \ReflectionException
     */
    public function test_unexisting_post_id_generates_404_response(): void
    {
        $randomUuid = $this->uuidFactory->random();

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->get('/api/posts/'.$randomUuid);

        $response->assertNotFound();
    }

    public function test_existing_post_id_generates_200_response_with_correct_post_data(): void
    {
        /*
         * We could do this if we only wanted to get the id, but we also want to check for the
         * correctness of the data.
         *
        $uuid = $db->getDoctrineConnection()->fetchOne(
            query: 'select id from posts where user_id = :user_id',
            params: ['user_id' => (string)$this->user->id()],
        );
        */

        /** @var Post $post */
        $post = $this->entityManager
            ->getRepository(Post::class)
            ->findOneBy(['user' => $this->user]);

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->get('/api/posts/'.$post->id());

        $response->assertOk();
        $this->assertEquals($post->user()->id(), $response->json('user.id'));
    }

}
