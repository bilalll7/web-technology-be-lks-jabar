<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\VoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('reset_password', [AuthController::class, 'reset']);
});
Route::get('poll', [PollController::class, 'index']);
Route::post('poll', [PollController::class, 'create']);
Route::get('poll/{id}', [PollController::class, 'show']);
Route::delete('poll/{id}', [PollController::class, 'destroy']);
Route::post('poll/{poll_id}/vote/{choice_id}',[VoteController::class, 'create']);
Route::get('validate/poll/{poll_id}',[VoteController::class, 'getVoted']);