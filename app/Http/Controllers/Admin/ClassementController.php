<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Epreuve;
use Illuminate\Http\Request;

class ClassementController extends Controller
{
    public function index()
    {
        // Classement des établissements par nombre d'épreuves
        $classement = User::where('role', 'etablissement')
            ->withCount('epreuves')
            ->orderBy('epreuves_count', 'desc')
            ->paginate(20);

        return view('admin.classement.index', compact('classement'));
    }
}