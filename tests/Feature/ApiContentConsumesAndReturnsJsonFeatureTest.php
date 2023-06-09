<?php declare(strict_types=1);

namespace Tests\Feature;

use Faker\Generator as Faker;
use Tests\TestCase;

final class ApiContentConsumesAndReturnsJsonFeatureTest extends TestCase
{
    private readonly array $input;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Faker $faker */
        $faker = $this->app->get(Faker::class);

        $this->input = [
            'email' => $faker->email(),
            'password' => $faker->password(),
        ];
    }

    public function test_no_application_json_in_the_accept_header_generates_a_406_response(): void
    {
        $response = $this
            ->withHeader('Accept', 'text/html')
            ->post('/api/auth', $this->input);

        $response->assertStatus(406);
    }

    public function test_no_application_json_in_the_content_type_header_generates_a_415_response_on_post_requests(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->post('/api/auth', $this->input);

        $response->assertUnsupportedMediaType();
    }

    public function test_no_application_json_in_the_content_type_header_does_not_generate_a_415_response_on_get_requests(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'text/html')
            ->get('/api/jsonapi/posts/12');

        $response->assertOk();
    }
}
