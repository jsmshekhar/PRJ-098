<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KycApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\CommonDataController;

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
    Route::get('/faqs', [CommonDataController::class, 'getFaqs']);
    Route::get('/get-complaint-category', [CommonDataController::class, 'complainCategory']);
    Route::post('get-near-hub-center', [CommonDataController::class, 'getNearHubCenter']);
    Route::post('payment-webhook', [WebhookController::class, 'paymentWebhook']);
});


Route::middleware(['auth:rider-api'])->namespace('Api')->prefix('v1')->group(function () {
    Route::get('/logout', [AuthApiController::class, 'logout']);
    Route::get('/rider-details', [AuthApiController::class, 'getRiderDetails']);
    Route::get('/profile-category', [KycApiController::class, 'profileCategory']);
    Route::get('/vehicle-preferences', [KycApiController::class, 'vehiclePreferences']);
    Route::get('/vehicle-details/{slug}', [KycApiController::class, 'vehicleDetails']);

    Route::post('create-order', [KycApiController::class, 'createOrder']);
    Route::post('update-kys-steps', [KycApiController::class, 'updateKycSteps']);
    Route::get('/get-kys-step', [KycApiController::class, 'getKycStep']);

    Route::post('create-complaint', [CommonDataController::class, 'createComplaint']);
    Route::get('/get-complaints', [CommonDataController::class, 'getComplaints']);
    Route::post('service-request', [CommonDataController::class, 'serviceRequest']);
    Route::post('return-exchange-request', [CommonDataController::class, 'returnExchangeRequest']);
    Route::get('/get-current-order', [CommonDataController::class, 'getCurrentOrder']);
    Route::post('update-payment-status', [KycApiController::class, 'updatePaymentStatus']);

    Route::get('/get-transactions', [CommonDataController::class, 'getTransactions']);
    Route::get('/get-ev-details', [CommonDataController::class, 'getEvDetails']);

    Route::get('/get-upcomming-rent', [CommonDataController::class, 'getUpcommingRent']);
    Route::post('pay-upcomming-rent', [CommonDataController::class, 'payUpcommingRent']);
    Route::get('/get-notification', [CommonDataController::class, 'getNotification']);

    Route::get('/get-kyc-documents', [CommonDataController::class, 'getKycDocuments']);
    Route::post('/upload-kyc-documents', [CommonDataController::class, 'uploadKycDocuments']);
    Route::get('/get-your-orders', [CommonDataController::class, 'getYourOrders']);

});
