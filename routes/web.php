<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::group(['prefix' => 'account'], function(){

    Route::group(['middleware' => 'guest'], function(){
        Route::get('register', [AccountController::class, 'register'])
        ->name('account.register');
        Route::post('register', [AccountController::class, 'store'])
        ->name('account.store');
        Route::get('login', [AccountController::class, 'login'])
        ->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])
        ->name('account.authenticate');
    });

    Route::group(['middleware' => 'auth'], function(){
        
        Route::get('profile', [AccountController::class, 'profile'])
        ->name('account.profile');
        Route::get('logout', [AccountController::class, 'logout'])
        ->name('account.logout');
        Route::put('update-profile/{user}', [AccountController::class, 'updateProfile'])
        ->name('account.updateProfile');
    });

});