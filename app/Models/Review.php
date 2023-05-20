<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    use HasUuids;


    protected $fillable = [
        'id',
        'rating',
        'content',
    ];


    public function bookable(){
        return $this->belongsTo(Bookable::class);
    }

    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
