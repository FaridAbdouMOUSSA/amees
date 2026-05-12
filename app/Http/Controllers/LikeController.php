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

        $like = Like::where('user_id', Auth::id())
                    ->where('epreuve_id', $id)
                    ->first();

        if ($like) {
            // 💔 unlike
            $like->delete();
        } else {
            // ❤️ like
            Like::create([
                'user_id' => Auth::id(),
                'epreuve_id' => $id
            ]);

            // 🔔 notification (option AMEES)
            Notification::create([
                'user_id' => $epreuve->user_id,
                'type' => 'like',
                'message' => Auth::user()->name . " a aimé votre épreuve"
            ]);
        }

        return back();
    }
}