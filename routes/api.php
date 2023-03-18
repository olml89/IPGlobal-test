<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Middleware\TokenAuthenticate as LaravelTokenAuthenticateMiddleware;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Get\LaravelGetController as LaravelGetPostController;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Get\LaravelGetRemoteController as LaravelGetRemotePostController;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Publish\LaravelPublishController as LaravelPublishPostController;
use olml89\IPGlobalTest\Security\Infrastructure\Http\LaravelAuthenticationController;

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

Route::post('/auth', LaravelAuthenticationController::class);
Route::middleware(LaravelTokenAuthenticateMiddleware::class)->post('/posts', LaravelPublishPostController::class);
Route::get('/jsonapi/posts/{id}', LaravelGetRemotePostController::class);
Route::get('/posts/{id}', LaravelGetPostController::class);
