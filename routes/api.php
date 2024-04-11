<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\VerificationController;

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

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);

});



// Route::prefix('ad')->middleware('web')->group(function () {
//     Route::post('step1', [AdController::class, 'stepOne']);
//     Route::post('step2', [AdController::class, 'stepTwo']);
//     Route::post('step3', [AdController::class, 'stepThree']);
// });

Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');
Route::apiResource('permissions', PermissionController::class)->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('ads', AdController::class)->middleware('auth:sanctum');
Route::apiResource('comments', CommentController::class)->middleware('auth:sanctum');
Route::get('/live', [AdController::class, 'getAds'])->middleware('auth:sanctum');
Route::get('/livesearch', [AdController::class, 'searchAds'])->middleware('auth:sanctum');
Route::get('/search', [AdController::class, 'search'])->middleware('auth:sanctum');
Route::patch('/ads/{id}/status', [AdController::class, 'updateSold'])->middleware('auth:sanctum');
Route::get('/getAdUser', [AdController::class, 'getAdUser'])->middleware('auth:sanctum');
Route::get('/myAds', [AdController::class, 'myAds'])->middleware('auth:sanctum');
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('subcategories', SubCategoryController::class)->middleware('auth:sanctum');


Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('index');
Route::get('/subcategories/{id}', [SubCategoryController::class, 'show'])->name('showSubcategories');
Route::controller(SubCategoryController::class)->middleware('auth:sanctum')->group(function(){
    // Route::get('/add-status', 'adStatus')->name('adStatus');
    Route::post('/subcategories', 'store')->name('store');
    Route::put('/subcategories/{id}', 'update')->name('update');
    Route::delete('/subcategories/{id}', 'delete')->name('delete');
 });




Route::get('/subscriptions-list', [SubscriptionController::class, 'list'])->name('list');
Route::controller(SubscriptionController::class)->middleware('auth:sanctum')->group(function(){
    // Route::get('/add-status', 'adStatus')->name('adStatus');
    Route::post('/create-subscription', 'createSubscription')->name('createSubscription');
    Route::get('/showSubscription', 'show')->name('showSubscription');
    Route::get('/getSubscriptionId', 'getSubscriptionId')->name('getSubscriptionId');
    Route::put('/activateSubscription/{id}', 'activate')->name('activateSubscription'); 
    Route::put('/statusSubscription/{id}', 'status')->name('SubscriptionStatus');
    Route::get('/adStatus', 'adStatus')->name('adStatus');

 });

 Route::controller(AuthController::class)->group(function() {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/confirm', 'confirm');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user-role', [UserController::class, 'getUserRoles'])->middleware('auth:sanctum');
Route::post('/upload_avatar', [AuthController::class, 'upload_user_photo'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/unreadNotifications', [CommentController::class, 'unreadNotifications']);
    Route::get('/markAsRead', [CommentController::class, 'markAsRead']);
});
Route::get('/ad/{id}/{notificationid}', [AdController::class, 'show'])->middleware('auth:sanctum');

// Route::controller(AuthController::class)->group(function(){
//     Route::post('/send-registration', 'sendsignup')->name('sendSignUp');
//     Route::get('/send-registration-mail/{email}', 'sendsignupmail')->name('sendSignUpMail');
// });
// Route::get('/email/verify/send', [VerificationController::class, 'sendMail']);
// Route::get('email/verify', [VerificationController::class, 'verify'])->middleware(middleware:'signed')->name(name:'verify-email');
