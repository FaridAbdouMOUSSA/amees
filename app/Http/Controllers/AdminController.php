<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Epreuve;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminController extends Controller 
{

    public function etablissements(Request $request): View|LengthAwarePaginator
    {
        $etablissements = User::where('role', 'etablissement')
            ->when($request->certifie !== null, function($q) use ($request) {
                $q->where('certifie', $request->certifie);
            })
            ->when($request->q, function($q) use ($request) {
                $q->where(function($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.trim($request->q).'%')
                         ->orWhere('email', 'like', '%'.trim($request->q).'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $etablissements->getCollection()->transform(function (User $user) {
            $user->epreuves_count = $user->epreuves()->count();
            $user->downloads_total = $user->epreuves()->sum('downloads');
            return $user;
        });

        return view('admin.etablissements', compact('etablissements'));
    }


    public function dashboard(): View
    {
        $stats = [
            'total_epreuves' => Epreuve::count(),
            'total_etablissements' => User::where('role', 'etablissement')->count(),
            'total_certifies' => User::where('role', 'etablissement')->where('certifie', 1)->count(),
            'total_downloads' => Epreuve::sum('downloads'),
        ];

        $today = today()->toDateString();
        $nouveaux = Epreuve::whereDate('created_at', $today)->count();
        
        $lastCheck = session('last_notification_check', now()->subDay()->toDateString());
        if ($lastCheck !== $today) {
            session(['notifications_read' => false, 'last_notification_check' => $today]);
        }
        
        $stats['nouveaux_aujourdhui'] = session('notifications_read', false) ? 0 : $nouveaux;
        $stats['nouveaux_aujourdhui_reel'] = $nouveaux;

        $top_etablissements = User::where('role', 'etablissement')
            ->withCount('epreuves')
            ->orderBy('epreuves_count', 'desc')
            ->limit(5)
            ->get();

        $activite_recente = Epreuve::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'top_etablissements', 'activite_recente'));
    }


    public function valider(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'etablissement') {
            return back()->with('error', '❌ Seuls les établissements peuvent être certifiés.');
        }
        
        if ($user->certifie) {
            return back()->with('warning', '⚠️ Cet établissement est déjà certifié.');
        }
        
        $user->certifie = true;
        $user->save();
        
        return back()->with('success', '✅ Établissement <strong>' . $user->name . '</strong> certifié avec succès !');
    }


    public function decertifier(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'etablissement') {
            return back()->with('error', '❌ Seuls les établissements peuvent être décertifiés.');
        }
        
        if (!$user->certifie) {
            return back()->with('warning', '⚠️ Cet établissement n\'est pas certifié.');
        }
        
        $user->certifie = false;
        $user->save();
        
        return back()->with('warning', '🔄 Établissement <strong>' . $user->name . '</strong> décertifié.');
    }

    public function profilEtablissement(User $user): View
    {
        if ($user->role !== 'etablissement') {
            abort(404, 'Établissement non trouvé');
        }
        $epreuves = $user->epreuves()->withCount('likes')->latest()->paginate(12);
        return view('etablissements.profil', compact('user', 'epreuves'));
    }

    public function editProfilEtablissement(User $user): View
    {
        if ($user->role !== 'etablissement' || $user->id !== Auth::id()) {
            abort(403);
        }
        $communes = ['Adjarra','Adja-Ouèrè','Agbangnizoun','Aguégués','Akpro-Missérété','Allada','Aplahoué','Athiémé','Avrankou','Banikoara','Bantè','Bassila','Bembéréké','Bohicon','Bonou','Bopa','Boukoumbé','Cobly','Comé','Copargo','Covè','Cotonou','Dassa-Zoumè','Dangbo','Djakotomey','Djidja','Djougou','Dogbo','Glazoué','Gogounou','Grand-Popo','Houéyogbé','Ifangni','Kalalé','Kandi','Karimama','Kérou','Kétou','Klouékanmè','Kouandé','Kpomassè','Lalo','Lokossa','Malanville','Matéri','Natitingou','N\'Dali','Nikki','Ouidah','Ouaké','Ouèssè','Ouinhi','Parakou','Péhunco','Pèrèrè','Pobè','Porto-Novo','Sakété','Savalou','Savè','Ségbana','Sèmè-Kpodji','Sinendé','Sô-Ava','Tanguiéta','Tchaourou','Toffo','Tori-Bossito','Toviklin','Toucountouna','Za-Kpota','Zagnanado','Zè','Zogbodomey'];
        return view('etablissements.edit-profil', compact('user', 'communes'));
    }

    public function updateProfilEtablissement(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'directeur' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'lien_localisation' => 'nullable|url',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($user->role !== 'etablissement' || $user->id !== Auth::id()) {
            abort(403);
        }

        if ($request->name !== $user->name) {
            if ($user->derniere_modification_nom && 
                now()->diffInDays(Carbon::parse($user->derniere_modification_nom)) < 365) {
                return back()->with('error', 'Nom changeable 1x/an seulement !');
            }
            $user->derniere_modification_nom = now();
        }

        if ($request->hasFile('photo_profil')) {
            if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            $photoPath = $request->file('photo_profil')->store('photos-profils', 'public');
            $user->photo_profil = $photoPath;
        }

        $user->update([
            'name' => $request->name,
            'telephone' => $request->telephone,
            'directeur' => $request->directeur,
            'commune' => $request->commune,
            'description' => $request->description,
            'lien_localisation' => $request->lien_localisation,
        ]);

        return redirect()->route('etablissement.profil', $user->fresh())
                         ->with('success', 'Profil mis à jour !');
    }

    public function classementPublic(): View
    {
        $users = User::where('role', 'etablissement')
            ->withCount('epreuves')
            ->get()
            ->map(function (User $user) {
                $user->score = $user->epreuves_count * 10;
                return $user;
            })
            ->sortByDesc('score')
            ->take(10);

        return view('ranking.top', compact('users'));
    }

    public function classementAdmin(): View
    {
        $stats = [
            'total_epreuves' => Epreuve::count(),
            'total_etablissements' => User::where('role', 'etablissement')->count(),
            'total_certifies' => User::where('role', 'etablissement')->where('certifie', 1)->count(),
            'total_downloads' => Epreuve::sum('downloads'),
        ];

        $classement = User::select('users.*')
            ->withCount('epreuves as epreuves_count')
            ->where('role', 'etablissement')
            ->orderBy('epreuves_count', 'desc')
            ->paginate(20);


        $top_etablissements = User::where('role', 'etablissement')
            ->withCount('epreuves')
            ->orderBy('epreuves_count', 'desc')
            ->limit(5)
            ->get();

        $activite_recente = Epreuve::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

 
        return view('admin.classement', compact('stats', 'classement', 'top_etablissements', 'activite_recente'));
    }

    public function checkNotifications(Request $request): JsonResponse
    {
        $today = today()->toDateString();
        $nouveaux = Epreuve::whereDate('created_at', $today)->count();
        
        return response()->json([
            'nouveaux' => $nouveaux,
            'read' => session('notifications_read', false)
        ]);
    }

    public function markNotificationsAsRead(Request $request): JsonResponse
    {
        session(['notifications_read' => true]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Notifications marquées comme lues !',
            'count' => 0
        ]);
    }


public function epreuves(Request $request)
{
    $epreuves = Epreuve::with(['user'])
        ->when($request->q, function($query) use ($request) {
            $query->where('titre', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->q}%"));
        })
        ->latest()
        ->paginate(12);

    return view('admin.epreuves.index', compact('epreuves'));
}
}