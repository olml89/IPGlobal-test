<?php declare(strict_types=1);

namespace Tests\Unit\Post;

use Faker\Generator as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use olml89\IPGlobalTest\Post\Application\Retrieve\RetrieveUseCase;
use olml89\IPGlobalTest\Post\Domain\RemotePostRetrievingException;
use Tests\TestCase;
use Tests\Unit\Post\JsonPlaceholderTypicode\PostDataGenerator;
use Tests\Unit\Post\JsonPlaceholderTypicode\UserDataGenerator;

final class RetrieveUseCaseTest extends TestCase
{
    private readonly RetrieveUseCase $retrieveUseCase;
    private readonly Faker $faker;
    private readonly int $id;
    private MockHandler $requests;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
        $this->id = $this->faker->randomNumber();
        $this->requests = new MockHandler();
        $this->setGuzzleClient();
        $this->retrieveUseCase = $this->app->get(RetrieveUseCase::class);
    }

    private function setGuzzleClient(): void
    {
        $this->app->singleton(Client::class, function(): Client {
            return new Client(['handler' => HandlerStack::create($this->requests)]);
        });
    }

    public function test_that_unexisting_remote_api_throws_domain_error(): void
    {
        $this->requests->append(
            new ConnectException(
                message: 'Error Communicating with Server',
                request: new Request('GET', sprintf('/posts/%s', $this->id)),
            )
        );

        $this->expectException(RemotePostRetrievingException::class);

        $this->retrieveUseCase->retrieve($this->faker->randomNumber());
    }

    public function test_that_unexisting_post_id_throws_domain_error(): void
    {
        $this->requests->append(
            new ClientException(
                message: 'Post not found',
                request: new Request('GET', sprintf('/posts/%s', $this->id)),
                response: new Response(404),
            )
        );

        $this->expectException(RemotePostRetrievingException::class);

        $this->retrieveUseCase->retrieve($this->faker->randomNumber());
    }

    public function test_that_valid_post_id_returns_valid_post(): void
    {
        $userId = $this->faker->randomNumber();
        $postData = (new PostDataGenerator($this->faker))->withId($this->id)->withUserId($userId);
        $userData = (new UserDataGenerator($this->faker))->withId($userId);

        $this->requests->append(
            new Response(
                status: 200,
                body: (string)$postData,
            ),
            new Response(
                status: 200,
                body: (string)$userData
            ),
        );

        $postResult = $this->retrieveUseCase->retrieve($this->id);

        $this->assertEquals($this->id, $postResult->id);
    }
}
