<?php declare(strict_types=1);

namespace Post;

use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\InvalidAutoIncrementalIdException;
use olml89\IPGlobalTest\Post\Application\PublishData;
use olml89\IPGlobalTest\Post\Application\PublishUseCase;
use Tests\TestCase;

final class PublishUseCaseTest extends TestCase
{
    private readonly PublishUseCase $publishUseCase;
    private readonly Faker $faker;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->publishUseCase = $this->app->get(PublishUseCase::class);
        $this->faker = $this->app->get(Faker::class);
    }

    public function test_that_invalid_user_id_generates_error(): void
    {
        $publishData = new PublishData(
            user_id: 0,
            title: $this->faker->title(),
            body: $this->faker->text(),
        );

        $this->expectException(InvalidAutoIncrementalIdException::class);

        $this->publishUseCase->publish($publishData);
    }

    public function test_result_includes_post_id(): void
    {
        $publishData = new PublishData(
            user_id: $this->faker->randomNumber(),
            title: $this->faker->title(),
            body: $this->faker->text(),
        );

        $publishResult = $this->publishUseCase->publish($publishData);

        $this->assertIsInt($publishResult->id);
    }
}
