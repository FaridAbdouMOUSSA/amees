<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Epreuve;
use App\Models\Like;

class EpreuveController extends Controller
{

public function update(Request $request, User $user)
{
    // Vérifier que c'est bien l'utilisateur connecté
    if (Auth::id() !== $user->id) {
        abort(403, 'Accès non autorisé');
    }

    $request->validate([
        'name'              => 'required|string|max:255',
        'commune'           => 'nullable|string|max:100',
        'telephone'         => 'nullable|string|max:20',
        'directeur'         => 'nullable|string|max:150',
        'description'       => 'nullable|string|max:1000',
        'lien_localisation' => 'nullable|url|max:500',
        'photo_profil'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // Mise à jour des informations
    $user->update([
        'name'              => $request->name,
        'commune'           => $request->commune,
        'telephone'         => $request->telephone,
        'directeur'         => $request->directeur,
        'description'       => $request->description,
        'lien_localisation' => $request->lien_localisation,
    ]);

    // Gestion de la photo de profil
    if ($request->hasFile('photo_profil')) {
        // Supprimer l'ancienne photo si elle existe
        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $path = $request->file('photo_profil')->store('photos/profil', 'public');
        $user->update(['photo_profil' => $path]);
    }

    // Rafraîchir le modèle pour être sûr
    $user->refresh();

    return redirect()->route('etablissement.profil', $user)
                     ->with('success', 'Profil mis à jour avec succès !');
}    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'titre'        => 'required|string|max:255',
            'classe'       => 'required|string|max:50',
            'matiere'      => 'required|string|max:100',
            'type_epreuve' => 'required|string|max:50',
            'semestre'     => 'nullable|string|max:10',
            'serie'        => 'nullable|string|max:10',           // ← ajouté
            'description'  => 'nullable|string',
            'fichier'      => 'required|file|mimes:pdf|max:10000',
        ]);

        $path = $request->file('fichier')->storeAs(
            'epreuves',
            Str::uuid() . '.pdf',
            'local'
        );

        Epreuve::create([
            'titre'        => $request->titre,
            'classe'       => $request->classe,
            'matiere'      => $request->matiere,
            'type_epreuve' => $request->type_epreuve,
            'semestre'     => $request->type_epreuve === 'Examen Blanc' ? null : $request->semestre,
            'serie'        => $request->serie,                    // ← AJOUTÉ
            'description'  => $request->description,
            'annee'        => now()->year,
            'type'         => $request->type_epreuve,
            'fichier'      => $path,
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('epreuves.index')
                        ->with('success', 'Épreuve publiée avec succès !');
    }

// Optionnel : tu peux aussi mettre à jour search() de la même façon
    public function search(Request $request)
    {
        // Même logique que index() pour la cohérence
        return $this->index($request); // ou dupliquer si tu veux garder search() séparé
    }

public function index(Request $request)
{
    $query = Epreuve::with(['user'])
                ->withCount('likes')
                ->latest();

    // Filtres
    if ($request->filled('classe')) {
        $query->where('classe', $request->classe);           // LIKE pas toujours nécessaire
    }
    if ($request->filled('matiere')) {
        $query->where('matiere', $request->matiere);
    }
    if ($request->filled('type_epreuve')) {
        $query->where('type_epreuve', $request->type_epreuve);
    }
    if ($request->filled('semestre')) {
        $query->where('semestre', $request->semestre);
    }
    if ($request->filled('serie')) {
        $query->where('serie', $request->serie);
    }
    if ($request->filled('annee')) {
        $query->where('annee', $request->annee);
    }

    $epreuves = $query->paginate(12);

    $likedIds = Auth::check()
                ? Like::where('user_id', Auth::id())->pluck('epreuve_id')->toArray()
                : [];

    // ==================== PARTIE AJAX (INFINITE SCROLL) ====================
    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
        try {
            $html = view('epreuves.partials.epreuve-card', compact('epreuves', 'likedIds'))->render();
            
            return response()->json([
                'epreuves_html' => $html,
                'has_more'      => $epreuves->hasMorePages()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Affichage normal
    return view('epreuves.index', compact('epreuves', 'likedIds'));
}
    public function download($id)
    {
        $epreuve = Epreuve::findOrFail($id);

        if (!Storage::disk('local')->exists($epreuve->fichier)) {
            abort(404, 'Fichier introuvable.');
        }

        $epreuve->increment('downloads');

        return Storage::disk('local')->download(
            $epreuve->fichier,
            $epreuve->titre . '.pdf'
        );
    }

    public function create()
    {
        return view('epreuves.create');
    }

    public function feed()
    {
        $epreuves = Epreuve::with(['user'])
            ->withCount('likes')
            ->latest()
            ->paginate(12);

        $likedIds = Auth::check()
            ? Like::where('user_id', Auth::id())->pluck('epreuve_id')->toArray()
            : [];

        return view('feed', compact('epreuves', 'likedIds'));
    }

    public function show(Epreuve $epreuve)
    {
        $epreuve->load('user');

        $likedIds = Auth::check()
            ? Like::where('user_id', Auth::id())->pluck('epreuve_id')->toArray()
            : [];

        return view('epreuves.show', compact('epreuve', 'likedIds'));
    }

public function destroy(Epreuve $epreuve)
{
    if (auth()->id() !== $epreuve->user_id) {
        abort(403, 'Accès refusé.');
    }

    try {
        // Supprimer le fichier PDF
        if ($epreuve->fichier && Storage::disk('public')->exists($epreuve->fichier)) {
            Storage::disk('public')->delete($epreuve->fichier);
        }

        $epreuve->delete();

        return redirect()
            ->route('etablissement.profil', auth()->user())
            ->with('success', '✅ Épreuve supprimée avec succès !');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Erreur lors de la suppression.');
    }
}
}