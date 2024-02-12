<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['UserID', 'RoomID', 'HotelID', 'CheckInDate', 'CheckOutDate','status','days'];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
     public function hotel(){
        return $this->belongsTo(Hotel::class,'HotelID', 'id');
    }
}
