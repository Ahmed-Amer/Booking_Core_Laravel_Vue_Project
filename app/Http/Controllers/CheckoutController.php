<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Booking;
use App\Models\Bookable;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'bookings' => 'required|array|min:1',
            'bookings.*.bookable_id' => 'required|exists:bookables,id',
            'bookings.*.from' => 'required|date|after_or_equal:today',
            'bookings.*.to' => 'required|date|after_or_equal:bookings.*.from',
            'customer.first_name' => 'required|min:2',
            'customer.last_name' => 'required|min:2',
            'customer.street' => 'required|min:2',
            'customer.city' => 'required|min:2',
            'customer.email' => 'required|email',
            'customer.country' => 'required|min:2',
            'customer.state' => 'required|min:2',
            'customer.zip' => 'required|min:2',
            'auth_user_id' => '',
        ]);

        //make sure before save booking and make checkout that bookings dates still available
        $data = array_merge($data , $request->validate([
            'bookings.*' => ['required' , function ($attribute , $value , $fail){
                $bookable = Bookable::findOrFail($value['bookable_id']);

                if(!$bookable->availableFor($value['from'] , $value['to'])){
                    $fail("The object is not available for the given dates!");
                }
            }]
        ]));

        $bookingsData = $data['bookings'];
        $addressData = $data['customer'];
        $authId = $data['auth_user_id'];
  
        //loop for different bookings and save each one 
        $bookings = collect($bookingsData)->map(function ($bookingItem) use ($addressData , $authId) {

            $booking = new Booking();
            $booking->from = $bookingItem['from'];
            $booking->to = $bookingItem['to'];
            $booking->total_price = $bookingItem['total_price'];
            $booking->bookable_id = $bookingItem['bookable_id'];
            $booking->user_id = $authId;
            $booking->address()->associate(Address::create($addressData)); //link booking to this created address
            $booking->save();

            return $booking;
        });

        return $bookings;
    }
}
