<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingReviewresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         return [
            'booking_id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'bookable' => new BookableShowReviewResource($this->bookable)
        ];
    }
}
