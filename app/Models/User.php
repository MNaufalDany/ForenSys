<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function registeredCases()
    {
        return $this->hasMany(AutopsyCase::class, 'registration_officer_id');
    }

    public function assignedCases()
    {
        return $this->hasMany(AutopsyCase::class, 'assigned_doctor_id');
    }

    public function scopeDoctors($query)
    {
        return $query
            ->where('role', 'dokter')
            ->where('status', 'active');
    }

}
