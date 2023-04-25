<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TravelPassReservations;

class TravelPassApplications extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reservation()
    {
        return $this->belongsTo(TravelPassReservations::class, 'reservation_id', 'id');
    }
}
