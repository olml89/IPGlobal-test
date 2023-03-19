<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Web\LaravelListController as WebListPostsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', WebListPostsController::class);

