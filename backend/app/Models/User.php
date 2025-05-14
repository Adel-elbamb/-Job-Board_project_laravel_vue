<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function hasRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $roleToCheck) {
            if (strtolower($this->role) === strtolower($roleToCheck)) {
                return true;
            }
        }

        return false;
    }
}
