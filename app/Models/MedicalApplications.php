<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MedicalReservation;

class MedicalApplications extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'status',
        'reference_code',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function appointment()
    {
        return $this->belongsTo(MedicalReservation::class, 'appointment_id', 'id');
    }


}
