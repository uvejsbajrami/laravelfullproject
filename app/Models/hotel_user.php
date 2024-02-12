<?php

namespace App\Models;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Relations\Pivot;

class hotel_user extends Pivot
{
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class)->withPivot('rating')->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('rating')->withTimestamps();
    }
}
