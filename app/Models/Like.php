<?php

namespace App\Models;

use App\Models\User;
use App\Models\Epreuve;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'epreuve_id'
    ];

    // 👤 relation user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 📚 relation epreuve
    public function epreuve()
    {
        return $this->belongsTo(Epreuve::class);
    }
}