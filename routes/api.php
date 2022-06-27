<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
});

/**
 * Api Resource example
 * Verb          Path                        Action  Route Name
    GET           /users                      index   users.index
    POST          /users                      store   users.store
    GET           /users/{user}               show    users.show
    PUT|PATCH     /users/{user}               update  users.update
    DELETE        /users/{user}               destroy users.destroy
 */

/**
 * as admin
 */
Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {

    /**
     * crud users
     */
    Route::apiResource(
        'users',
        \App\Http\Controllers\Api\UserController::class,
        ['as' => 'api.users']
    );

    /**
     * daftar pinjam
     */
    Route::get(
        'booking',
        [\App\Http\Controllers\Api\BookingController::class, 'index']
    )->name('api.booking');
});

/**
 * as user
 */
Route::middleware(['auth:sanctum', 'ability:user'])->group(function () {
 //TODO api untuk pinjam buku belum sempat dibuat dikarenakan waktu
});
