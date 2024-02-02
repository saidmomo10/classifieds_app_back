<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SubscriptionController;

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

Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');

Route::controller(SubscriptionController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/add-subscriptions', 'addSubscriptions')->name('addSubscriptions');
    Route::post('/create-subscription', 'createSubscription')->name('createSubscription');
    Route::get('/subscriptions-list', 'list')->name('list');
    Route::get('showSubscription/{id}', 'show')->name('showSubscription');
    Route::put('/activateSubscription/{id}', 'activate')->name('activateSubscription');
    Route::put('/statusSubscription/{id}', 'status')->name('SubscriptionStatus');
    // Route::put('/affect-file/{id}', 'affect')->name('affectFile');

 });

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);