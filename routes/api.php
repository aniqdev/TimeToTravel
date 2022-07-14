<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\ApiV1Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RouteReviewController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'route'], function () {
        Route::get('info/{id}', [RoutesController::class, 'info']);
        Route::get('sights/{id}', [RoutesController::class, 'sights']);
        Route::get('city/{limit}/{skip}', [RoutesController::class, 'city']);
    });
    
    // Route::post('upload/avatar', [UsersController::class, 'uploadAvatar']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', [AuthController::class, 'apiSignup']);
    Route::post('login', [AuthController::class, 'apiLogin']);
});

Route::post('/cities/activate', [CityController::class, 'activate']);
Route::post('/RouteReview/activate', [RouteReviewController::class, 'activate']);
Route::post('/RouteReview/delete', [RouteReviewController::class, 'delete']);

Route::controller(ApiV1Controller::class)->prefix('v1')->group(function () {

    // Route::post('androidLogin', 'androidLogin');
    // Route::post('deviceLogin', 'androidLogin');
    Route::post('loginByDeviceId', 'androidLogin');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('androidUserInfo', 'androidUserInfo');
        Route::any('addFavorite', 'addFavorite');
        Route::any('removeFavorite', 'removeFavorite');
        Route::any('downloadRoute', 'downloadRoute');
        Route::post('addReview', 'addReview');
    });


    Route::get('getCities', 'getCities');
    Route::get('findCities', 'findCities');
    Route::get('getRoutesInCity', 'getRoutesInCity');
    Route::get('getRoute', 'getRoute');
    Route::get('findRoutesInCity', 'findRoutesInCity');
    Route::get('getNearestRoutes', 'getNearestRoutes');
    Route::get('getRoutesByAuthor', 'getRoutesByAuthor');
    Route::get('findAuthorRoutes', 'findAuthorRoutes');
    Route::get('getAuthor', 'getAuthor');
    Route::get('getFavorites', 'getFavorites');
    Route::get('getViewed', 'getViewed');
    Route::get('getDownloaded', 'getDownloaded');
    Route::get('getUserReviews', 'getUserReviews');
    Route::get('reviewExists', 'reviewExists');
    Route::get('getRouteAuthorReviews', 'getRouteAuthorReviews');
});
