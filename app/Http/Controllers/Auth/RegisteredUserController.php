<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    $domain = strtolower(trim(substr(strrchr($value, "@"), 1)));

                    $allowedDomains = [
                        'gmail.com',
                        'yahoo.com',
                        'yahoo.fr',
                        'outlook.com',
                        'hotmail.com',
                        'live.com',
                        'icloud.com',
                        'proton.me',
                        'protonmail.com',
                    ];

                    if (!in_array($domain, $allowedDomains)) {
                        $fail('Veuillez utiliser une adresse email valide (Gmail, Yahoo, Outlook, Hotmail, iCloud ou Proton).');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:eleve,etablissement'],
        ], [
            'email.required' => 'Veuillez saisir une adresse email.',
            'email.email'    => 'Veuillez entrer une adresse email valide.',
            'email.unique'   => 'Cette adresse email est déjà utilisée.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'etablissement') {
            $user->certifie = false;
            $user->save();
        }

        event(new Registered($user));
        Auth::login($user);

        if ($user->role === 'etablissement') {
            return redirect()
                ->route('etablissement.profil', $user)
                ->with('success', '✅ Compte établissement créé avec succès !');
        }

        return redirect()
            ->route('dashboard')
            ->with('success', '✅ Compte créé avec succès !');
    }
}