<?php

namespace App\Http\Controllers;

use App\Models\Bookable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookablePriceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, Request $request)
    {
        $data = $request->validate([
            'from' => 'required|date_format:Y-m-d|after_or_equal:today',
            'to' => 'required|date_format:Y-m-d|after_or_equal:from'
        ]);

        $bookable = Bookable::findOrFail($id);

        $days = (new Carbon($data['from']))->diffInDays(new Carbon($data['to'])) + 1;
        $total_price = $days * $bookable->price;

        return response()->json([
            'data' => [
                'total_price' => $total_price,
                'breakdown' => [
                    $bookable->price => $days
                ]
            ]
                ]);
    }
}
