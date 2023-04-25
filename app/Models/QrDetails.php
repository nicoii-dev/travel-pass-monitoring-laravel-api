<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TravelPassApplications;

class QrDetails extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function application()
    {
        return $this->belongsTo(TravelPassApplications::class, 'application_id', 'id');
    }
}
