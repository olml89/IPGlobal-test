<?php declare(strict_types=1);

namespace Test\Feature\Post;

use Faker\Generator as Faker;
use olml89\IPGlobalTest\Post\Application\PostResult;
use Tests\TestCase;

final class RetrieveRemoteFeatureTest extends TestCase
{
    private readonly Faker $faker;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
    }

    public function test_invalid_id_returns_404_response(): void
    {
        // JsonPlaceholderTypicode API has 100 posts, so the last valid id is 100
        $id = 101;

        $response = $this->withHeader('Accept', 'application/json')->get('/api/jsonapi/posts/'.$id);

        $response->assertNotFound();
    }

    public function test_valid_id_returns_200_response(): void
    {
        // JsonPlaceholderTypicode API has 100 posts, so the last valid id is 100
        $id = $this->faker->randomNumber(1, 100);

        $response = $this->withHeader('Accept', 'application/json')->get('/api/jsonapi/posts/'.$id);

        $response->assertOk();
    }
}
