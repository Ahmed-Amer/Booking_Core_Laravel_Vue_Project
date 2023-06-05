<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BookableReviewIndexResource extends JsonResource
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
            'content' => $this->content,
            'rating' => $this->rating,
            'created_at' => $this->created_at->diffForHumans(),
            'user_name' => $this->user_id ? User::findOrFail($this->user_id)->name : null
        ];
    }
}
