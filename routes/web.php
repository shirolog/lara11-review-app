<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])
->name('home.index');
Route::get('/book/{book}', [HomeController::class, 'detail'])
->name('home.detail');
Route::post('/save-book-review', [HomeController::class, 'saveReview'])
->name('home.saveReview');



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
        Route::get('change-password', [AccountController::class, 'changePass'])
        ->name('account.changePass');
        Route::put('change-password', [AccountController::class, 'updatePass'])
        ->name('account.updatePass');
        Route::put('update-profile/{user}', [AccountController::class, 'updateProfile'])
        ->name('account.updateProfile');
        Route::get('my-reviews', [AccountController::class, 'myReviews'])
        ->name('account.myReviews');
        Route::get('my-reviews/{review}', [AccountController::class, 'editReview'])
        ->name('account.editReview');
        Route::put('my-reviews/{review}', [AccountController::class, 'updateReview'])
        ->name('account.updateReview');
        Route::delete('my-delete-reviews/{review}', [AccountController::class, 'deleteReview'])
        ->name('account.deleteReview');

        Route::group(['middleware' => 'check-admin'], function(){

            Route::get('books', [BookController::class, 'index'])
            ->name('book.index');
            Route::get('books/create', [BookController::class, 'create'])
            ->name('book.create');
            Route::post('books/create', [BookController::class, 'store'])
            ->name('book.store');
            Route::get('books/edit/{book}', [BookController::class, 'edit'])
            ->name('book.edit');
            Route::put('books/edit/{book}', [BookController::class, 'update'])
            ->name('book.update');
            Route::delete('books/{book}', [BookController::class, 'destroy'])
            ->name('book.destroy');

            Route::get('reviews', [ReviewController::class, 'index'])
            ->name('review.index');
            Route::get('reviews/{review}', [ReviewController::class, 'edit'])
            ->name('review.edit');
            Route::put('reviews/{review}', [ReviewController::class, 'update'])
            ->name('review.update');
            Route::delete('delete-reviews/{review}', [ReviewController::class, 'destroy'])
            ->name('review.destroy');
        });
    });

});