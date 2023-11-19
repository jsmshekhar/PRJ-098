<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->prefix('v1')->group(function () {

    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);

});


//Route::middleware('auth:api')->get('/rider-details', 'AuthApiController@getRiderDetails');

Route::middleware(['auth:rider-api'])->namespace('Api')->prefix('v1')->group(function () {
    Route::get('/logout', [AuthApiController::class, 'logout']);
    Route::get('/rider-details', [AuthApiController::class, 'getRiderDetails']);
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });