<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Resources\BookingReviewresource;

class BookingReviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($review_key, Request $request)
    {
        if(Booking::findBookingByReviewkey($review_key) != null){
            return new BookingReviewresource(Booking::findBookingByReviewkey($review_key));
        }else{
             abort(404);
        }
    }
}
