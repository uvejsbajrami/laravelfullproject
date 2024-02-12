<?php

namespace App\Models;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'HotelID',
        'name',
    ];
    public function hotel(){
        return $this->belongsTo(Hotel::class,'HotelID', 'id');
    }
}
