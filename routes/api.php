<?php

use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReturnitemController;
use App\Http\Controllers\SerialnumbersController;
use App\Http\Controllers\SerialsController;
use App\Http\Controllers\SyncStatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('employee', EmployeeController::class);
    Route::apiResource('supplier', SupplierController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('expense', ExpenseController::class);
    Route::apiResource('customer', CustomerController::class);

    // Legacy casing aliases used by existing Vue clients
    Route::apiResource('Customer', CustomerController::class);

    Route::post('/salary/paid/{id}', [SalaryController::class, 'Paid']);
    Route::get('/salary', [SalaryController::class, 'AllSalary']);
    Route::get('/salary/view/{id}', [SalaryController::class, 'ViewSalary']);
    Route::get('/edit/salary/{id}', [SalaryController::class, 'EditSalary']);
    Route::post('/salary/update/{id}', [SalaryController::class, 'SalaryUpdate']);
    Route::post('/stock/update/{id}', [ProductController::class, 'StockUpdate']);

    Route::get('/getting/product/{id}', [PosController::class, 'GetProduct']);

    Route::post('/cart/add/{id}', [CartController::class, 'AddToCart']);
    Route::get('/cart/product', [CartController::class, 'CartProduct']);
    Route::post('/cart/remove/{id}', [CartController::class, 'removeCart']);
    Route::post('/cart/increment/{id}', [CartController::class, 'Increment']);
    Route::post('/cart/decrement/{id}', [CartController::class, 'Decrement']);
    Route::get('/vats', [CartController::class, 'Vats']);

    Route::post('/orderdone', [PosController::class, 'OrderDone']);

    Route::get('/allOrder', [OrderController::class, 'AllOrder']);
    Route::get('/orders', [OrderController::class, 'TodayOrder']);
    Route::get('/order/details/{id}', [OrderController::class, 'OrderDetails']);
    Route::get('/order/orderdetails/{id}', [OrderController::class, 'OrderDetailsAll']);
    Route::post('/search/order', [OrderController::class, 'SearchOrderDate']);
    Route::post('/search/month', [OrderController::class, 'SearchMonth']);

    Route::get('/today/sell', [PosController::class, 'TodaySell']);
    Route::get('/today/income', [PosController::class, 'TodayIncome']);
    Route::get('/today/due', [PosController::class, 'TodayDue']);
    Route::get('/today/expense', [PosController::class, 'TodayExpense']);
    Route::get('/stockout', [PosController::class, 'Stockout']);

    Route::post('/activitylog', [ActivitylogController::class, 'savelog']);
    Route::get('/activitylog', [ActivitylogController::class, 'getlog']);

    Route::middleware('role.admin')->group(function () {
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'getTheUser']);
        Route::post('/users/{id}', [UserController::class, 'updateUser']);
        Route::post('/sync/now', [SyncStatusController::class, 'syncNow']);
    });

    Route::get('/sync/status', [SyncStatusController::class, 'status']);

    Route::post('/serials', [SerialsController::class, 'saveSerials']);
    Route::get('/serials', [SerialsController::class, 'getSerials']);
    Route::post('/returns', [ReturnitemController::class, 'saveReturns']);
    Route::get('/returns', [ReturnitemController::class, 'getReturns']);
    Route::put('/returns/{id}', [ReturnitemController::class, 'updateReturns']);

    Route::get('/productSerials', [SerialnumbersController::class, 'getSerialNumbers']);
    Route::post('/saveSerialNumbers', [SerialnumbersController::class, 'saveSerialNumbers']);
    Route::get('/productSerials/{id}', [SerialnumbersController::class, 'getProductSerials']);
    Route::patch('/productSerials/{id}', [SerialnumbersController::class, 'SerialNumberUpdate']);
});
