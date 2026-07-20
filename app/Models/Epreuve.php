<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epreuve extends Model
{
    protected $fillable = [
        'titre',
        'classe',
        'matiere',
        'type_epreuve',
        'semestre',
        'serie',           // ← doit être là
        'description',
        'annee',
        'type',
        'fichier',
        'user_id',
        'downloads'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}