<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function toggle($id)
    {
        $favori = Favori::where('user_id', Auth::id())
                        ->where('epreuve_id', $id)
                        ->first();

        if ($favori) {
            $favori->delete(); // ❌ retirer
        } else {
            Favori::create([
                'user_id' => Auth::id(),
                'epreuve_id' => $id
            ]); // ⭐ ajouter
        }

        return back();
    }
}