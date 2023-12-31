<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\RefundMgmtController;
use App\Http\Controllers\RiderOrderController;

Route::get('/orders', [RiderOrderController::class, 'index'])->name('order-list');
Route::post('/orders', [RiderOrderController::class, 'assignEv'])->name('assign-ev-customer');
Route::post('/change-kyc-status', [RiderController::class, 'changeKycStatus'])->name('change-kyc-status');
Route::post('/update-rider-details', [RiderController::class, 'updateRiderDetails'])->name('update-rider-details');
?>
