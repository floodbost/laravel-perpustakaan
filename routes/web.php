<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/dashboard');
});


/**
 * all role
 */
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', function () {
        //dd(auth()->user()->isAdmin());
        return view('dashboard');
    })->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::put('profile', [
        \App\Http\Controllers\ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::resource('books', \App\Http\Controllers\BookController::class);
});

/**
 * admin role
 */
Route::middleware(['auth', 'role:admin'])->group(function() {

    Route::get('/search', [
        \App\Http\Controllers\SearchController::class,
        'index'
    ])->name('search');


    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__.'/auth.php';
