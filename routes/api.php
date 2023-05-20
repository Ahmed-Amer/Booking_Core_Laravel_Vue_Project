<?php

use App\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookableController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\BookableReviewController;
use App\Http\Controllers\BookableAvailabilityController;
use App\Http\Controllers\BookablePriceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserBookingsController;

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



Route::get('bookables', [BookableController::class , 'index']);

Route::get('bookables/{id}', [BookableController::class , 'show']);
Route::get('bookables/{bookable}/availability', BookableAvailabilityController::class)->name('bookables.availability.show');
Route::get('bookables/{bookable}/reviews', BookableReviewController::class)->name('bookable.reviews');
Route::get('reviews/{id}', [ReviewController::class , 'show']);
Route::post('reviews', [ReviewController::class , 'store']);

Route::get('booking-by-review/{review_key}', BookingReviewController::class)->name('booking.review-key');
Route::get('bookables/{bookable}/price', BookablePriceController::class)->name('bookable.price');
Route::post('checkout', CheckoutController::class)->name('checkout');

Route::get('booking-by-userid/{user_id}', UserBookingsController::class)->name('booking.user-id');
