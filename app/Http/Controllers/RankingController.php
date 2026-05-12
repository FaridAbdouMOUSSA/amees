<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
        public function dashboard()

    {

        $stats = [

            'total_epreuves' => Epreuve::count(),

            'total_etablissements' => User::where('role', 'etablissement')->count(),

            'etablissements_certifies' => User::where('role', 'etablissement')->where('certifie', 1)->count(),

            'total_downloads' => Epreuve::sum('downloads'),

            'epreuves_aujourdhui' => Epreuve::whereDate('created_at', today())->count(),

        ];


        $top_etablissements = User::where('role', 'etablissement')

            ->withCount('epreuves')

            ->orderBy('epreuves_count', 'desc')

            ->limit(5)

            ->get();


        $recent_epreuves = Epreuve::with('user')

            ->orderBy('created_at', 'desc')

            ->limit(10)

            ->get();


        return view('admin.dashboard', compact('stats', 'top_etablissements', 'recent_epreuves'));

    }
    // Route publique (tous les utilisateurs) ET admin détecte automatiquement
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Si admin, redirige vers la vue admin
        if ($user && $user->isAdmin()) {
            return $this->adminIndex($request);
        }
        
        // Vue publique TOP 10
        $users = User::select('users.*')
            ->withCount('epreuves')
            ->where('score', '>', 0)
            ->where('role', 'etablissement') // Seulement les établissements
            ->orderBy('score', 'desc')
            ->limit(10)
            ->get();
            
        return view('ranking.top', compact('users'));
    }
    
    // Méthode spécifique admin (optionnelle)
    public function adminIndex(Request $request)
    {
        $classement = User::select('users.*')
            ->withCount('epreuves as epreuves_count')
            ->where('role', 'etablissement')
            ->orderBy('score', 'desc')
            ->paginate(20);
            
        return view('admin.classement.index', compact('classement'));
    }
}