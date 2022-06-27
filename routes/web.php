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
        return view('dashboard');
    })->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::put('profile', [
        \App\Http\Controllers\ProfileController::class,
        'update'
    ])->name('profile.update');
});

/**
 * admin role
 */
Route::middleware(['auth', 'role:admin'])->group(function() {
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__.'/auth.php';
