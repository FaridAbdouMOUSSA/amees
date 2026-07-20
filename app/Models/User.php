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
        'name', 'email', 'password', 'role', 'classe', 'serie', 
        'certifie', 'telephone', 'directeur', 'commune', 
        'description', 'lien_localisation', 'photo_profil', 
        'derniere_modification_nom', 'logo'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    // Casts correct Laravel 10+
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'certifie' => 'boolean',
    'derniere_modification_nom' => 'datetime',
    'telephone' => 'array',        // ← Ajoute ou vérifie cette ligne
];

        public function likes()
    {
        return $this->hasMany(Like::class);
    }
    // Dans app/Models/User.php
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    // Ajoute dans app/Models/User.php
    public function epreuves()
    {
        return $this->hasMany(Epreuve::class);
    }
    public function getTelephoneFormattedAttribute()
{
    $phones = $this->telephone;
    
    if (is_string($phones)) {
        $phones = json_decode($phones, true) ?? [$phones];
    }
    if (!is_array($phones)) {
        $phones = [$phones];
    }

    return array_filter(array_map('trim', $phones));
}
}
