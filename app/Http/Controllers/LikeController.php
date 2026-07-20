<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Epreuve;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($id)
    {
        $epreuve = Epreuve::findOrFail($id);

        if ($epreuve->user_id === Auth::id()) {
            return response()->json(['error' => 'Vous ne pouvez pas liker votre propre épreuve.'], 403);
        }

        $like = Like::where('user_id', Auth::id())
                    ->where('epreuve_id', $id)
                    ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id'    => Auth::id(),
                'epreuve_id' => $id,
            ]);

            // Notification (optionnel)
            if ($epreuve->user_id !== Auth::id()) {
                \App\Models\Notification::create([
                    'user_id' => $epreuve->user_id,
                    'type'    => 'like',
                    'message' => Auth::user()->name . " a aimé votre épreuve",
                ]);
            }
            $liked = true;
        }

        $count = $epreuve->likes()->count();

        return response()->json([
            'liked' => $liked,
            'count' => $count
        ]);
    }
}