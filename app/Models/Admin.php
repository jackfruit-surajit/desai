<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'admins';
    
    protected $fillable = [
        'name',
        'type_id',
        'role_id',
        'email',
        'phone',
        'email_otp',
        'phone_otp',
        'password',
        'image',
        'area_id',
        'vehicle_id',
        'licence_no',
        'licence_expiry',
        'remember_token',
        'activation_token',
        'last_login',
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
        'licence_expiry' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Accessor for license_no
     */
    public function getLicenseNoAttribute()
    {
        return $this->attributes['licence_no'] ?? null;
    }

    /**
     * Mutator for license_no
     */
    public function setLicenseNoAttribute($value)
    {
        $this->attributes['licence_no'] = $value;
    }

    /**
     * Accessor for license_expiry
     */
    public function getLicenseExpiryAttribute()
    {
        return $this->attributes['licence_expiry'] ?? null;
    }

    /**
     * Mutator for license_expiry
     */
    public function setLicenseExpiryAttribute($value)
    {
        $this->attributes['licence_expiry'] = $value;
    }
}
