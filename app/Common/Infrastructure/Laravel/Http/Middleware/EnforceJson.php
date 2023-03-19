<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

final class EnforceJson
{
    /**
     * @throws NotAcceptableHttpException | UnsupportedMediaTypeHttpException
     */
    public function handle(Request $request, Closure $next, string ...$guards): JsonResponse
    {
        // Http Not Acceptable
        if (!$request->wantsJson()) {
            return new JsonResponse(
                data: ['message' => 'This api only returns data in \'application/json\' format'],
                status: 406,
            );
        }

        // Http Unsupported Media Type
        // Safe methods are the read-only ones: HEAD, GET, OPTIONS, TRACE
        if (!$request->isMethodSafe() && !$request->isJson()) {
            return new JsonResponse(
                data: ['message' => 'This api only consumes data in \'application/json\' format'],
                status: 415,
            );
        }

        return $next($request);
    }
}
