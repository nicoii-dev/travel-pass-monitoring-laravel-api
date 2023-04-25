<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Models\CurrentAddress;
use App\Models\PermanentAddress;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'dob',
        'civil_status',
        'phone_number',
        'image',
        'role',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // the route where you will enter your new password
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:3000/resetpass?token='.$token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function currentAddress()
    {
        return $this->hasOne(CurrentAddress::class, 'user_id', 'id');
    }

    public function permanentAddress()
    {
        return $this->hasOne(PermanentAddress::class, 'user_id', 'id');
    }
}
