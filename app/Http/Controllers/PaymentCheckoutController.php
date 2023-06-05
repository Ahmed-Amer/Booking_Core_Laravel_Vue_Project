<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Booking;
use App\Models\Bookable;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaymentCheckoutController extends Controller
{


    
    public function checkout(Request $request){

        $payment = 1000;
        $this->processTransaction($payment);

        // $data = $request->validate([
        //     'bookings' => 'required|array|min:1',
        //     'bookings.*.bookable_id' => 'required|exists:bookables,id',
        //     'bookings.*.from' => 'required|date|after_or_equal:today',
        //     'bookings.*.to' => 'required|date|after_or_equal:bookings.*.from',
        //     'customer.first_name' => 'required|min:2',
        //     'customer.last_name' => 'required|min:2',
        //     'customer.street' => 'required|min:2',
        //     'customer.city' => 'required|min:2',
        //     'customer.email' => 'required|email',
        //     'customer.country' => 'required|min:2',
        //     'customer.state' => 'required|min:2',
        //     'customer.zip' => 'required|min:2',
        //     'auth_user_id' => '',
        // ]);

        // //make sure before save booking and make checkout that bookings dates still available
        // $data = array_merge($data , $request->validate([
        //     'bookings.*' => ['required' , function ($attribute , $value , $fail){
        //         $bookable = Bookable::findOrFail($value['bookable_id']);

        //         if(!$bookable->availableFor($value['from'] , $value['to'])){
        //             $fail("The object is not available for the given dates!");
        //         }
        //     }]
        // ]));

        // $bookingsData = $data['bookings'];
        // $addressData = $data['customer'];
        // $authId = $data['auth_user_id'];

        // $payment = 0;
        //  //loop for different bookings and get total price
        //  collect($bookingsData)->map(function ($bookingItem) use ($payment) {
        //     $payment += $bookingItem['total_price'];
        // });
  
        // if($this->processTransaction($payment)){

        //     //loop for different bookings and save each one 
        //     $bookings = collect($bookingsData)->map(function ($bookingItem) use ($addressData , $authId) {
    
        //         $booking = new Booking();
        //         $booking->from = $bookingItem['from'];
        //         $booking->to = $bookingItem['to'];
        //         $booking->total_price = $bookingItem['total_price'];
        //         $booking->bookable_id = $bookingItem['bookable_id'];
        //         $booking->user_id = $authId;
    
        //         $booking->address()->associate(Address::create($addressData)); //link booking to this created address
        //         $booking->save();
    
        //         return $booking;
        //     });
    
    
        //     return response()->json([
        //         'bookings' => $bookings,
        //         'message' => 'Transaction completed.'
        //     ]);
        // }else{
        //     return response()->json([
        //         'message' => 'Transaction Failed.'
        //     ], 404);
        // }

    }


    public function success(Request $request){

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            // return true;

            // return response()->json([
            //     'message' => 'Transaction completed.'
            // ]);

            return redirect()
                ->url('/')
                ->with('success', 'Transaction complete.');
        } else {

            // return false;

            // return response()->json([
            //     'message' => 'Something went wrong.'
            // ], 404);

            return redirect()
                ->url('/')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }


    public function cancel(Request $request){
        // return false;

        // return response()->json([
        //     'message' => 'You have canceled the transaction.'
        // ], 404);

        return redirect()
        ->url('/')
        ->with('error', $response['message'] ?? 'Something went wrong.');
    }




     /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction($amount)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('checkout-success'),
                "cancel_url" => route('checkout-cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount,
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            // return false;
            // return response()->json([
            //     'message' => 'Something went wrong.'
            // ], 404);

            return redirect()
                ->url('/')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->url('/')
                ->with('error', 'Something went wrong.');

            // return response()->json([
            //     'message' => 'Something went wrong.'
            // ], 404);
            // return false;
        }
    }
    

}
