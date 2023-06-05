<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    

    public function show($id){
        return new ReviewResource(Review::findOrFail($id));
    }

    public function store(Request $request){
        $data = $request->validate([
            'id' => 'required|size:36|unique:reviews',
            'rating' => 'required|in:1,2,3,4,5',
            'content' => 'required|min:2',
            'user_id' => ''
        ]);
       
        $booking = Booking::findBookingByReviewkey($data['id']);
        if($booking == null){
            return abort(404);
        }else{
            //reset booking review_key as review will be added
            $booking->review_key = '';
            $booking->save();

            //add review
            $review = Review::make($data);
            $review->booking_id = $booking->id;
            $review->bookable_id = $booking->bookable_id;
            $review->user_id = $request->user_id;
            $review->save();
            
            return new ReviewResource($review);

        }
    }
}
