<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\PasswordSetController;
use App\Http\Controllers\DataExportController;
use App\Http\Controllers\LiveTrackingController;
use App\Http\Controllers\ProductCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect('admin/login');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['register' => false, 'login' => true, 'vefify' => false]);

    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        //Roles Routes
        Route::get('/roles', [RoleUserController::class, 'getRoles'])->name('roles');
        Route::post('/add-update-role', [RoleUserController::class, 'addUpdateRole'])->name('add-update-role');
        Route::post('/role-delete/{slug}', [RoleUserController::class, 'deleteRole'])->name('role-delete');
        Route::post('/user-role-permission', [RoleUserController::class, 'allowPermission'])->name('user-role-permission');

        //Users Routes
        Route::get('/users', [RoleUserController::class, 'getusers'])->name('users');
        Route::post('/add-update-user', [RoleUserController::class, 'addUpdateUser'])->name('add-update-user');
        Route::post('/user-delete/{slug}', [RoleUserController::class, 'deleteUser'])->name('user-delete');
        Route::post('/user-status-changed', [RoleUserController::class, 'userStatusChanged'])->name('user-status-changed');

        //Hub Routes
        Route::get('/distributed-hubs', [HubController::class, 'getHubs'])->name('distributed-hubs');
        Route::post('/add-update-hub', [HubController::class, 'addUpdateHub'])->name('add-update-hub');
        Route::post('/hub-delete/{slug}', [HubController::class, 'deleteHub'])->name('hub-delete');
        Route::get('/view-hub/{slug}/{param}', [HubController::class, 'viewHub'])->name('hub-view');
        Route::post('/hub-status-changed', [HubController::class, 'hubStatusChanged'])->name('hub-status-changed');

        //Rider Routes
        Route::get('/customer-managements', [RiderController::class, 'getRiders'])->name('customer-managements');
        Route::get('/customer-view/{slug}', [RiderController::class, 'viewRider'])->name('customer-view');
        Route::post('/rider-status-changed', [RiderController::class, 'riderStatusChanged'])->name('rider-status-changed');

        //Complain & Query Routes
        Route::get('/complain-queries', [ComplainController::class, 'getComplains'])->name('complain-queries');
        Route::post('/complain-status-changed', [ComplainController::class, 'complainStatusChanged'])->name('complain-status-changed');
        Route::post('/change-complain-assignment', [ComplainController::class, 'complainAssignmentChanged'])->name('change-complain-assignment');
        Route::get('/complain-categories', [ComplainController::class, 'getComplainCategories'])->name('complain-categories');
        Route::post('/add-update-complain-category', [ComplainController::class, 'addUpdateComplainCategories'])->name('add-update-complain-category');
        Route::post('/caomplain-category-delete/{slug}', [ComplainController::class, 'deleteComplainCategory'])->name('caomplain-category-delete');

        ///Notification Routes
        Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
        Route::get('/create-notification/{param}', [NotificationController::class, 'createNotification'])->name('create-notification');
        Route::post('/add-notification', [NotificationController::class, 'addNotification'])->name('add-notification');
        Route::get('/edit-notification/{param}/{slug}', [NotificationController::class, 'editNotification'])->name('edit-notification');
        Route::post('/update-notification/{slug}', [NotificationController::class, 'updateNotification'])->name('update-notification');
        Route::post('/notification-delete/{slug}', [NotificationController::class, 'deleteNotification'])->name('notification-delete');
        Route::post('/notification-status-changed', [NotificationController::class, 'notificationStatusChanged'])->name('notification-status-changed');
        Route::post('/add-user-base', [NotificationController::class, 'addUserBase'])->name('add-user-base');

        //Products Routes
        Route::get('/products', [ProductController::class, 'getProducts'])->name('products');
        Route::post('/add-update-product', [ProductController::class, 'addUpdateProduct'])->name('add-update-product');
        Route::post('/product-delete/{slug}', [ProductController::class, 'deleteProduct'])->name('product-delete');

        Route::get('/product/categories-and-types', [ProductCategoryController::class, 'getProductCategoryType'])->name('categories-and-types');
        Route::post('/add-update-product-category', [ProductCategoryController::class, 'addUpdateProductCategory'])->name('add-update-product-category');
        Route::post('/add-update-ev-type', [ProductCategoryController::class, 'addUpdateEvType'])->name('add-update-ev-type');

        Route::get('/tracking', [LiveTrackingController::class, 'index'])->name('live-tracking');
    });

    Route::get('/data-export', [DataExportController::class, 'dataExport'])->name('data-export');
    Route::get('password/set/{token}', [PasswordSetController::class, 'showSetPasswordForm'])->name('show-set-password-form');
    Route::post('password/set', [PasswordSetController::class, 'setPassword'])->name('set-password');
});
