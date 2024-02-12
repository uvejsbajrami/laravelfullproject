<?php

namespace App\Models;

use App\Models\Hotel;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'HotelID',
        'type',
        'capacity',
        'status',
        'room_number'
    ];
    public function hotel(){
        return $this->belongsTo(Hotel::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }

}
