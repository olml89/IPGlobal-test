<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Middleware\TokenAuthenticate as TokenAuthenticateMiddleware;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Api\Get\LaravelGetController as ApiGetPostController;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Api\Get\LaravelGetRemoteController as ApiGetRemotePostController;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Api\Post\LaravelPublishController as ApiPublishPostController;
use olml89\IPGlobalTest\Security\Infrastructure\Http\Api\LaravelAuthenticationController as ApiAuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth', ApiAuthenticationController::class);
Route::middleware(TokenAuthenticateMiddleware::class)->post('/posts', ApiPublishPostController::class);
Route::get('/jsonapi/posts/{id}', ApiGetRemotePostController::class);
Route::get('/posts/{id}', ApiGetPostController::class);
