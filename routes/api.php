<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KycApiController;
use App\Http\Controllers\Api\AuthApiController;

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
    Route::post('validate-user', [AuthApiController::class, 'validateUser']);
    Route::post('reset-new-password', [AuthApiController::class, 'resetPassword']);
    Route::post('/upload-file', [KycApiController::class, 'uploadFile']);
});


Route::middleware(['auth:rider-api'])->namespace('Api')->prefix('v1')->group(function () {
    Route::get('/logout', [AuthApiController::class, 'logout']);
    Route::get('/rider-details', [AuthApiController::class, 'getRiderDetails']);
    Route::get('/profile-category', [KycApiController::class, 'profileCategory']);
    Route::get('/vehicle-preferences', [KycApiController::class, 'vehiclePreferences']);
    Route::get('/vehicle-details/{slug}', [KycApiController::class, 'vehicleDetails']);

    Route::post('create-order', [KycApiController::class, 'createOrder']);
    Route::post('update-kys-steps', [KycApiController::class, 'updateKycSteps']);
});
