<?php

use App\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookableController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserBookingsController;
use App\Http\Controllers\BookablePriceController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\BookableReviewController;
use App\Http\Controllers\BookableAvailabilityController;
use App\Http\Controllers\SavedReportsController;

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


//admin bookables routes
Route::get('bookables', [BookableController::class , 'index']);
Route::get('bookables-for-datatables', [BookableController::class , 'dashboardBookables']);
Route::post('save-bookable', [BookableController::class , 'store']);
Route::delete('bookables/{id}', [BookableController::class , 'destroy']);

//admin bookings routes
Route::get('bookings-for-datatables', [BookingsController::class , 'index']);
Route::delete('bookings/{id}', [BookingsController::class , 'destroy']);

//admin users routes
Route::get('users-for-datatables', [UsersController::class , 'index']);
Route::delete('users/{id}', [UsersController::class , 'destroy']);
Route::post('save-user', [UsersController::class , 'store']);

//admin reports routes
Route::get('files-for-datatables', [SavedReportsController::class , 'index']);
Route::delete('reports/{id}', [SavedReportsController::class , 'destroy']);
Route::post('save-report', [SavedReportsController::class , 'store']);
Route::get('download_files/{id}', [SavedReportsController::class , 'download']);


Route::get('bookables/{id}', [BookableController::class , 'show']);
Route::get('bookables/{bookable}/availability', BookableAvailabilityController::class)->name('bookables.availability.show');
Route::get('bookables/{bookable}/reviews', BookableReviewController::class)->name('bookable.reviews');
Route::get('reviews/{id}', [ReviewController::class , 'show']);
Route::post('reviews', [ReviewController::class , 'store']);


Route::get('booking-by-review/{review_key}', BookingReviewController::class)->name('booking.review-key');
Route::get('bookables/{bookable}/price', BookablePriceController::class)->name('bookable.price');

Route::post('checkout', CheckoutController::class)->name('checkout');

Route::get('booking-by-userid/{user_id}', UserBookingsController::class)->name('booking.user-id');
