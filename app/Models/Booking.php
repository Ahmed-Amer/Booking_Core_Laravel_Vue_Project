<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = ['from', 'to'];

    public function bookable()
    {
        return $this->belongsTo('App\Models\Bookable');
    }

    public function reviews(){
        return $this->hasOne(Review::class);
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public static function findBookingByReviewkey($review_key) : ?Booking
    {
        return static::where('review_key' , $review_key)->with('bookable')->get()->first();
    }    

 

    //query scope method to check the dates overlapping
    //start with prefix scope
    public function scopeBetweenDates(Builder $query, $from, $to)
    {
        return $query->where('to', '>=', $from)
            ->where('from', '<=', $to);
    }

    //calling model life cycle hook named creating using boot function which executed
    //when model boot 
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            //add review_key when ever a new booking model created
            $booking->review_key = Str::uuid();
        });
    }
}
