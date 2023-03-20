<?php declare(strict_types=1);

namespace Test\Unit\Post;

use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Faker\Generator as Faker;
use olml89\IPGlobalTest\Post\Application\List\ListUseCase;
use olml89\IPGlobalTest\Post\Domain\PostRepository;
use Tests\PrepareDatabase;
use Tests\TestCase;

final class ListUseCaseTest extends TestCase
{
    use PrepareDatabase;

    private readonly Faker $faker;
    private readonly PostFactory $postFactory;
    private readonly UserFactory $userFactory;
    private readonly PostRepository $postRepository;
    private readonly ListUseCase $listUseCase;

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->migrate();

        $this->faker = $this->app->get(Faker::class);
        $this->postFactory = $this->app->get(PostFactory::class);
        $this->userFactory = $this->app->get(UserFactory::class);
        $this->postRepository = $this->app->get(PostRepository::class);
        $this->listUseCase = $this->app->get(ListUseCase::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    /**
     * @throws \ReflectionException
     */
    public function test_lists_all_the_requested_posts(): void
    {
        $numPosts = $this->faker->numberBetween(5, 10);

        for ($i = 0; $i < $numPosts; ++$i) {
            $post = $this->postFactory->random();
            $this->postRepository->save($post);
        }

        $listPosts = $this->listUseCase->all();

        $this->assertCount($numPosts + 1, $listPosts);
    }
}
