<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Epreuve;
use Illuminate\Support\Facades\DB;

class EpreuveController extends Controller
{   
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'classe' => 'required|string|max:50',
            'matiere' => 'required|string|max:100',
            'type_epreuve' => 'required|string|max:50',
            'semestre' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf|max:10000',
        ]);

        $fileName = time() . '.' . $request->fichier->extension();
        $request->fichier->move(public_path('uploads'), $fileName);

        Epreuve::create([
            'titre' => $request->titre,
            'classe' => $request->classe,
            'matiere' => $request->matiere,
            'type_epreuve' => $request->type_epreuve,
            'semestre' => $request->semestre,
            'description' => $request->description,
            'annee' => now()->year,
            'type' => $request->type_epreuve,
            'fichier' => $fileName,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('epreuves.index')
                        ->with('success', 'Épreuve publiée !');
    }

    public function search(Request $request)
    {
        $query = Epreuve::withCount('likes');

        if ($request->filled('classe')) {
            $query->where('classe', 'LIKE', '%' . $request->classe . '%');
        }
        if ($request->filled('matiere')) {
            $query->where('matiere', 'LIKE', '%' . $request->matiere . '%');
        }
        if ($request->filled('type_epreuve')) {
            $query->where('type_epreuve', 'LIKE', '%' . $request->type_epreuve . '%');
        }
        if ($request->filled('semestre')) {
            $query->where('semestre', 'LIKE', '%' . $request->semestre . '%');
        }

        $epreuves = $query->latest()->paginate(12);

        return view('epreuves.index', compact('epreuves'));
    }

    public function index(Request $request)
    {
        $query = Epreuve::with(['user'])
            ->withCount('likes')
            ->latest();

        $epreuves = $query->paginate(12);

        return view('epreuves.index', compact('epreuves'));
    }

    public function download($id)
    {
        $epreuve = Epreuve::findOrFail($id);

        $epreuve->increment('downloads');

        return response()->download(public_path('uploads/' . $epreuve->fichier));
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

        return view('feed', compact('epreuves'));
    }

    public function show(Epreuve $epreuve)
    {
        $epreuve->load('user');
        
        return view('epreuves.show', compact('epreuve'));
    }

    // 🔥 MÉTHODE LIKE AJAX (✅ DÉJÀ PRÊTE)
    public function toggleLike(Request $request, Epreuve $epreuve)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['error' => 'Non connecté'], 401);
        }
        
        $like = DB::table('likes')
            ->where('user_id', $userId)
            ->where('epreuve_id', $epreuve->id)
            ->first();
        
        if ($like) {
            DB::table('likes')->where('id', $like->id)->delete();
            $liked = false;
        } else {
            DB::table('likes')->insert([
                'user_id' => $userId,
                'epreuve_id' => $epreuve->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $liked = true;
        }
        
        $count = $epreuve->likes()->count();
        
        return response()->json([
            'liked' => $liked,
            'count' => $count
        ]);
    }
}