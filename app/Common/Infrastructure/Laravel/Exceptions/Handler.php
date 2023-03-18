<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use olml89\IPGlobalTest\Common\Domain\Exceptions\DomainException;
use olml89\IPGlobalTest\Common\Domain\Exceptions\NotFoundException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @throws Throwable
     */
    private function optionallyReportInfrastructureExceptionFromDomainException(DomainException $e): void
    {
        $infrastructureException = $e->getInfrastructureException();

        if (!is_null($infrastructureException)) {
            $this->report($infrastructureException);
        }
    }

    /**
     * @throws Throwable
     */
    protected function prepareException(Throwable $e): Throwable
    {
        // When we get to this point, the NotFoundException from the Domain has already been logged.
        // This also logs the previous underlying Infrastructure exception.
        if ($e instanceof NotFoundException) {
            $this->optionallyReportInfrastructureExceptionFromDomainException($e);

            $e = new NotFoundHttpException($e->getMessage());
        }

        // This converts value object validation exceptions to a 422 Unprocessable http response,
        // without having to use the ValidationException used by Laravel (binded to the Validator)
        if ($e instanceof ValueObjectException) {
            $this->optionallyReportInfrastructureExceptionFromDomainException($e);

            $e = new UnprocessableEntityHttpException($e->getMessage());
        }

        return parent::prepareException($e);
    }
}
