<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use App\Models\Image;
use App\Models\Booking;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'address',
        'contact',
        'price',
        'rating'
    ];
    public function room(){
         return  $this->hasMany(Room::class);
    }
    public function images(){
        return $this->hasMany(Image::class , 'HotelID', 'id');
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class , 'HotelID', 'id');
    }
    public function users()
{
    return $this->belongsToMany(User::class)->withPivot('rating')->withTimestamps();
}
}
