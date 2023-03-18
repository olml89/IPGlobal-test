<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use olml89\IPGlobalTest\Post\Infrastructure\Input\Get\LaravelGetController as LaravelGetPostController;
use olml89\IPGlobalTest\Post\Infrastructure\Input\Get\LaravelGetRemoteController as LaravelGetRemotePostController;
use olml89\IPGlobalTest\Post\Infrastructure\Input\Publish\LaravelPublishController as LaravelPublishPostController;
use olml89\IPGlobalTest\Security\Infrastructure\Input\LaravelAuthenticationController;

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
Route::post('/posts', LaravelPublishPostController::class);
Route::get('/jsonapi/posts/{id}', LaravelGetRemotePostController::class);
Route::get('/posts/{id}', LaravelGetPostController::class);
