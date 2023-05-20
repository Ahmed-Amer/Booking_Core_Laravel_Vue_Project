<?php

namespace App\Http\Controllers;

use App\Models\Bookable;
use App\Models\User;
use Illuminate\Http\Request;

class UserBookingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($user_id , Request $request)
    {
        $user = User::findOrFail($user_id);
        $bookings = $user->bookings;

        foreach ($bookings as $booking) {
                $bookable = Bookable::findOrFail($booking->bookable_id);
                $booking["title"] = $bookable->title; 
            }
            
      
        return $bookings;
    }
}
