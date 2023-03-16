<?php declare(strict_types=1);

namespace Post;

use Faker\Generator as Faker;
use Tests\TestCase;

final class PublishPostTest extends TestCase
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

    public function test_invalid_input_returns_422_response(): void
    {
        $input = [
            'user_id' => 0,
            'title' => $this->faker->title(),
            'body' => $this->faker->text(),
        ];

        $response = $this->withHeader('Accept', 'application/json')->post('/api/posts', $input);

        $response->assertUnprocessable();
    }

    public function test_valid_input_generates_201_response(): void
    {
        $input = [
            'user_id' => $this->faker->randomNumber(),
            'title' => $this->faker->title(),
            'body' => $this->faker->text(),
        ];

        $response = $this->withHeader('Accept', 'application/json')->post('/api/posts', $input);

        $response->assertCreated();
        $response->assertHeader('Location');
    }
}
