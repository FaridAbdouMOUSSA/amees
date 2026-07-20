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
            ->withCount('epreuves')                               // ✅ fix N+1
            ->withSum('epreuves as downloads_total', 'downloads') // ✅ fix N+1
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

        return view('admin.etablissements', compact('etablissements'));
    }


public function dashboard(Request $request)
{
    // ==================== STATISTIQUES GLOBALES ====================
$stats = [
    'total_epreuves'          => Epreuve::count(),
    'total_etablissements'    => User::where('role', 'etablissement')->count(),
    'total_certifies'         => User::where('role', 'etablissement')->where('certifie', 1)->count(),
    'total_non_certifies'     => User::where('role', 'etablissement')->where('certifie', 0)->count(), // ← Nouveau
    'total_eleves'            => User::where('role', 'eleve')->count(),
    'total_downloads'         => Epreuve::sum('downloads'),
    'total_likes'             => \App\Models\Like::count(),
    'nouveaux_aujourdhui'     => Epreuve::whereDate('created_at', today())->count(),
];

    $sort   = $request->get('sort', 'epreuves');
    $period = $request->get('period', 'all');

    // ==================== CLASSEMENT ====================
    $classementQuery = User::where('role', 'etablissement')
        ->withCount('epreuves')
        ->withSum('epreuves as total_downloads', 'downloads')
        ->withCount(['epreuves as total_likes' => fn($q) => $q->withCount('likes')]);

    // Filtre par période
    if ($period !== 'all') {
        $classementQuery->whereHas('epreuves', function($q) use ($period) {
            if ($period === 'month') {
                $q->whereMonth('created_at', now()->month);
            } elseif ($period === 'quarter') {
                $q->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
            } elseif ($period === 'year') {
                $q->whereYear('created_at', now()->year);
            }
        });
    }

    // Tri
    match ($sort) {
        'downloads' => $classementQuery->orderBy('total_downloads', 'desc'),
        'likes'     => $classementQuery->orderBy('total_likes', 'desc'),
        'score'     => $classementQuery->orderByRaw('(epreuves_count * 10 + COALESCE(total_downloads, 0)) DESC'),
        default     => $classementQuery->orderBy('epreuves_count', 'desc'),
    };

    $classement = $classementQuery->paginate(20)->appends(request()->query());

    // ==================== DONNÉES GRAPHES & ACTIVITÉ ====================
    $evolutionData = $this->getEvolutionData();
    $repartitionClasse = Epreuve::selectRaw('classe, COUNT(*) as total')
        ->groupBy('classe')
        ->pluck('total', 'classe')->toArray();

    $repartitionMatiere = Epreuve::selectRaw('matiere, COUNT(*) as total')
        ->groupBy('matiere')
        ->orderByDesc('total')
        ->limit(6)
        ->pluck('total', 'matiere')->toArray();

    $inscriptionsMensuelles = $this->getInscriptionsMensuelles();

    $activiteRecente = Epreuve::with('user')
        ->latest()
        ->limit(8)
        ->get();

    // ====================== AJAX ======================
    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
        return view('admin.partials.ranking', compact('classement', 'period', 'sort'))->render();
    }

    return view('admin.dashboard', compact(
        'stats', 
        'classement', 
        'evolutionData',
        'repartitionClasse', 
        'repartitionMatiere',
        'inscriptionsMensuelles',
        'activiteRecente',
        'sort', 
        'period'
    ));
}

// Méthodes auxiliaires
private function getEvolutionData()
{
    $data = Epreuve::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('mois')
        ->pluck('total', 'mois')
        ->toArray();

    $result = [];
    for ($i = 1; $i <= 12; $i++) {
        $result[] = $data[$i] ?? 0;
    }
    return $result;
}

private function getInscriptionsMensuelles()
{
    return User::selectRaw('MONTH(created_at) as mois, role, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('mois', 'role')
        ->get()
        ->groupBy('role')
        ->mapWithKeys(function($items, $role) {
            $data = array_fill(1, 12, 0);
            foreach ($items as $item) {
                $data[$item->mois] = $item->total;
            }
            return [$role => array_values($data)];
        });
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
        
        return back()->with('success', '✅ Établissement <strong>' . e($user->name) . '</strong> certifié avec succès !');
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
        
        return back()->with('warning', '🔄 Établissement <strong>' . e($user->name) . '</strong> décertifié.');
    }


public function profilEtablissement(Request $request, User $user)
{
    if ($user->role !== 'etablissement') {
        abort(404);
    }

    $query = $user->epreuves()
        ->with(['user'])
        ->withCount('likes')
        ->latest();

    $downloadsTotal = $user->epreuves()->sum('downloads');
    $totalLikes = \App\Models\Like::whereIn('epreuve_id', $user->epreuves()->pluck('id'))->count();

    // ✅ Important : Récupérer les likes de l'utilisateur connecté
    $likedIds = Auth::check()
                ? \App\Models\Like::where('user_id', Auth::id())
                    ->pluck('epreuve_id')
                    ->toArray()
                : [];

    $epreuves = $query->paginate(12);

    // ====================== AJAX LOAD MORE ======================
    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
        $page = $request->get('page', 1);
        
        $epreuves = $query->paginate(12, ['*'], 'page', $page);

        $epreuves_html = '';
        foreach ($epreuves as $epreuve) {
            $epreuves_html .= view('epreuves.partials.epreuve-card-profil', compact('epreuve', 'likedIds'))->render();
        }

        return response()->json([
            'epreuves_html' => $epreuves_html,
            'has_more' => $epreuves->hasMorePages(),
        ]);
    }

    return view('etablissements.profil', compact(
        'user',
        'epreuves',
        'downloadsTotal',
        'totalLikes',
        'likedIds'          // ← À AJOUTER
    ));
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
        'name'              => 'required|string|max:255',
        'telephone1'        => 'nullable|string|max:20',
        'telephone2'        => 'nullable|string|max:20',
        'directeur'         => 'nullable|string|max:255',
        'commune'           => 'nullable|string|max:100',
        'description'       => 'nullable|string|max:1000',
        'lien_localisation' => 'nullable|url',
        'photo_profil'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'telephone1' => [
        'nullable',
        'regex:/^(?:\+229)?01[0-9]{8}$/'
    ],
    'telephone2' => [
        'nullable',
        'regex:/^(?:\+229)?01[0-9]{8}$/'
    ],
    ]);

    if ($user->role !== 'etablissement' || $user->id !== Auth::id()) {
        abort(403);
    }

    // Vérification changement de nom (une fois par an)
    if ($request->name !== $user->name) {
        if ($user->derniere_modification_nom && 
            now()->diffInDays(Carbon::parse($user->derniere_modification_nom)) < 365) {
            return back()->withInput()->with('error', 'Le nom ne peut être modifié qu’une fois par an !');
        }
    }

    // Formatage des numéros béninois
    $telephones = array_filter([
        $this->formatBeninPhone($request->telephone1),
        $this->formatBeninPhone($request->telephone2),
    ]);

    $updateData = [
        'name'              => $request->name,
        'telephone'         => $telephones,        // ← Tableau
        'directeur'         => $request->directeur,
        'commune'           => $request->commune,
        'description'       => $request->description,
        'lien_localisation' => $request->lien_localisation,
    ];

    // Mise à jour du nom + date
    if ($request->name !== $user->name) {
        $updateData['derniere_modification_nom'] = now();
    }

    // Gestion de la photo de profil
    if ($request->hasFile('photo_profil')) {
        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }
        $path = $request->file('photo_profil')->store('photos-profils', 'public');
        $updateData['photo_profil'] = $path;
    }

    $user->update($updateData);

    return redirect()
        ->route('etablissement.profil', $user)
        ->with('success', '✅ Profil mis à jour avec succès !');
}


public function classementPublic()
{
    $users = User::where('role', 'etablissement')
        ->withCount('epreuves')                    // Nombre d'épreuves
        ->withSum('epreuves', 'downloads')         // ✅ Total des téléchargements (version simple)
        ->get()
        ->map(function (User $user) {
            // On renomme pour plus de clarté dans la vue
            $user->downloads_total = $user->epreuves_sum_downloads ?? 0;
            
            // Total likes (déjà corrigé précédemment)
            $user->total_likes = \App\Models\Like::whereIn(
                'epreuve_id', 
                $user->epreuves()->pluck('id')
            )->count();

            // Score global
            $user->score = ($user->epreuves_count * 10) 
                         + $user->downloads_total 
                         + ($user->total_likes * 2);

            return $user;
        })
        ->sortByDesc('epreuves_count')
        ->take(10);

    return view('ranking.top', compact('users'));
}


    public function classementAdmin(): View
    {
        $stats = [
            'total_epreuves'       => Epreuve::count(),
            'total_etablissements' => User::where('role', 'etablissement')->count(),
            'total_certifies'      => User::where('role', 'etablissement')->where('certifie', 1)->count(),
            'total_downloads'      => Epreuve::sum('downloads'),
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
            'read'     => session('notifications_read', false)
        ]);
    }


    public function markNotificationsAsRead(Request $request): JsonResponse
    {
        session(['notifications_read' => true]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Notifications marquées comme lues !',
            'count'   => 0
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
private function formatBeninPhone(?string $phone): ?string
{
    if (empty($phone)) {
        return null;
    }

    // Garder uniquement les chiffres
    $clean = preg_replace('/\D/', '', $phone);

    // Si l'utilisateur saisit +229...
    if (str_starts_with($clean, '229')) {
        $clean = substr($clean, 3);
    }

    // Un numéro béninois doit contenir exactement 10 chiffres
    if (strlen($clean) !== 10) {
        return null;
    }

    // Doit commencer par 01
    if (!str_starts_with($clean, '01')) {
        return null;
    }

    return '+229 ' . implode(' ', str_split($clean, 2));
}
private function phoneForInput(?string $phone): string
{
    if (!$phone) {
        return '';
    }

    return preg_replace('/[^0-9]/', '', str_replace('+229', '', $phone));
}

public function destroy(Epreuve $epreuve)
{
    // Vérification que l'utilisateur est bien le propriétaire
    if (auth()->id() !== $epreuve->user_id) {
        abort(403, 'Vous ne pouvez supprimer que vos propres épreuves.');
    }

    // Suppression du fichier PDF s'il existe
    if ($epreuve->fichier && Storage::disk('public')->exists($epreuve->fichier)) {
        Storage::disk('public')->delete($epreuve->fichier);
    }

    $epreuve->delete();

    return redirect()
        ->route('etablissement.profil', auth()->user())
        ->with('success', '✅ Épreuve supprimée avec succès !');
}
}