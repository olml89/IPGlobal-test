<?php declare(strict_types=1);

namespace Test\Feature\Post;

use Faker\Generator as Faker;
use Tests\TestCase;

final class RetrieveFeatureTest extends TestCase
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

        $response = $this->withHeader('Accept', 'application/json')->get('/api/posts/'.$id);

        $response->assertNotFound();
    }

    public function test_valid_id_returns_200_response(): void
    {
        // JsonPlaceholderTypicode API has 100 posts, so the last valid id is 100
        $id = $this->faker->randomNumber(1, 100);

        $response = $this->withHeader('Accept', 'application/json')->get('/api/posts/'.$id);
        $responseData = json_decode($response->getContent(), true);

        $response->assertOk();
        $this->assertEquals($id, $responseData['id']);
    }
}
