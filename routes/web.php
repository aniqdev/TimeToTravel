<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{  RoutesController,
                            AuthController,
                            AdminController,
                            UserController,
                            CityController,
                            RouteController,
                            RouteReviewController,
                            RouteImageController,
                        };
use Illuminate\Http\Request;

// Auth::routes();

Route::view('/pages/flyTo-1', 'pages.mapbox-flyTo-1');

Route::any('/echo', fn(Request $request) => $request->all())->name('echo');

Route::get('/storage/sight-images-previews/{image}', [RouteImageController::class, 'sightImagesThumb']);
Route::get('/storage/route-images-previews/{image}', [RouteImageController::class, 'routeImagesThumb']);

Route::group(['middleware' => ['auth:web',\App\Http\Middleware\SessionSettings::class]], function () {

    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/welcome', [AdminController::class, 'welcome'])->name('welcome');

    Route::get('/routes/searchAjax', [RouteController::class, 'searchAjax']);
    Route::get('/routes/search', [RouteController::class, 'search'])->name('routes.search');

    Route::get('/author/{user}', [UserController::class, 'showAutor'])->name('users.showAutor');

    Route::resources([
        'users' => UserController::class,
        'cities' => CityController::class,
        'routes' => RouteController::class,
        'routeReviews' => RouteReviewController::class,
    ]);

    Route::post('/routes/updateRouteFiles', [RouteController::class, 'updateRouteSightFiles']);
    Route::post('/routes/updateImageTitle', [RouteController::class, 'updateImageTitle']);
    Route::post('/routes/updateAudioTitle', [RouteController::class, 'updateAudioTitle']);
    Route::post('/routes/updateRouteFilesOrder', [RouteController::class, 'updateRouteSightFilesOrder']);
    Route::post('/routes/createSight', [RouteController::class, 'createSight']);
    Route::post('/routes/removeSight', [RouteController::class, 'removeSight']);
    Route::post('/routes/removeObjectFile', [RouteController::class, 'removeObjectFile']);
    Route::post('/routes/restore', [RouteController::class, 'restoreRoute']);

    Route::group(['middleware' => 'author'], function () {  
        
        Route::post('route', [RoutesController::class, 'create']);
        Route::post('sight', [RoutesController::class, 'addSight']);

        Route::get('route', [RoutesController::class, 'repopulateRoute'])->name('route');
        Route::get('sight', [RoutesController::class, 'repopulateSights'])->name('sight');
    });

    Route::get('settings', [AuthController::class, 'settings'])->name('settings');
    Route::post('settings', [AuthController::class, 'updateProfile']);
    Route::get('logout', [AuthController::class, 'webSignOut'])->name('logout');
});

Route::get('login', [AuthController::class, 'webLoginView'])->name('login');
Route::post('login', [AuthController::class, 'webLogin']);

Route::get('register', [AuthController::class, 'webRegisterView'])->name('register');
Route::post('register', [AuthController::class, 'webRegister']);



