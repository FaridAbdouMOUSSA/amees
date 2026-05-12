<?php

namespace App\Models;

use App\Models\User;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Epreuve extends Model
{
    protected $fillable = [
        'titre',
        'enseignant',
        'classe',
        'serie',
        'matiere',
        'annee',
        'semestre',
        'type',
        'type_epreuve',
        'fichier',
        'user_id',
        'downloads',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 🔥 MÉTHODE POUR VÉRIFIER SI UTILISATEUR A LIKÉ
    public function isLikedByUser()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getIsLikedAttribute()
    {
        return $this->isLikedByUser();
    }
}