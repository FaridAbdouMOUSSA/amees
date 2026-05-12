<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('register');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        // ✅ RÔLE PRIS DU FORMULAIRE (PAS FORCÉ !)
        // $data['role'] = 'eleve';  ← SUPPRIMÉ !

        $user = User::create($data);

        event(new Registered($user));

        // ✅ CONNEXION AUTOMATIQUE
        Auth::login($user);

        // 🔥 REDIRECTION SELON RÔLE
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        if ($user->role === 'etablissement') {
            return redirect('/epreuves')->with('success', '🏫 Bienvenue ! Publiez votre première épreuve.');
        }

        return redirect('/epreuves'); // élève
    }
}