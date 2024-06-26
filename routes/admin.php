<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ReturnExchangeController;
use App\Http\Controllers\RiderOrderController;

Route::get('/orders', [RiderOrderController::class, 'index'])->name('order-list');
Route::post('/orders', [RiderOrderController::class, 'assignEv'])->name('assign-ev-customer');
Route::post('/change-kyc-status', [RiderController::class, 'changeKycStatus'])->name('change-kyc-status');
Route::post('/update-rider-details', [RiderController::class, 'updateRiderDetails'])->name('update-rider-details');

Route::get('/return-exchange', [ReturnExchangeController::class, 'index'])->name('return-exchange');

Route::get('/return-view/{slug}', [ReturnExchangeController::class, 'returnView'])->name('return-view');
Route::get('/exchange-view/{slug}', [ReturnExchangeController::class, 'exchangeView'])->name('exchange-view');

Route::post('/return-evs/{slug}', [ReturnExchangeController::class, 'returnEvs'])->name('return-evs');
Route::post('/exchange-evs/{slug}', [ReturnExchangeController::class, 'exchangeEvs'])->name('exchange-evs');
Route::post('/pay-cod-rent', [RiderController::class, 'payCodRent'])->name('pay-cod-rent');

