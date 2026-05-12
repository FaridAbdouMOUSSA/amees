<?php

namespace App\Models;

use App\Models\Epreuve;
use App\Models\Like;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'classe',
        'serie',
        'logo',
        'certifie'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    // Casts correct Laravel 10+
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function epreuves()
    {
        return $this->hasMany(Epreuve::class);
    }

        public function likes()
    {
        return $this->hasMany(Like::class);
    }
    // Dans app/Models/User.php
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

}
