<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {


    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('me', [ApiAuthController::class, 'me']);
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::post('refresh', [ApiAuthController::class, 'refresh']);


    Route::prefix('task')
        ->group(function () {
            Route::post('index/{user_id}', [ApiTaskController::class, 'index']);
            Route::post('create', [ApiTaskController::class, 'create']);
            Route::post('update/{task_id}', [ApiTaskController::class, 'update']);
            Route::post('delete/{task_id}', [ApiTaskController::class, 'delete']);
        });
});
