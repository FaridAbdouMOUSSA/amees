<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EpreuveController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

// 🔥 GUEST
Route::get('/', fn() => view('home'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// 🔄 DASHBOARD → ÉPREUVES
Route::get('/dashboard', fn() => redirect()->route('epreuves.index'))
    ->middleware('auth')->name('dashboard');

// 🔥 AUTHENTIFIÉES (tous users)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Épreuves
    Route::prefix('epreuves')->name('epreuves.')->group(function () {
        Route::get('/', [EpreuveController::class, 'index'])->name('index');
        Route::get('/create', [EpreuveController::class, 'create'])->middleware('role:etablissement')->name('create');
        Route::post('/', [EpreuveController::class, 'store'])->middleware('role:etablissement')->name('store');
        Route::get('/search', [EpreuveController::class, 'search'])->name('search');
        Route::get('/{epreuve}', [EpreuveController::class, 'show'])->name('show');
        Route::get('/download/{epreuve}', [EpreuveController::class, 'download'])->name('download');
        Route::post('/{epreuve}/like', [EpreuveController::class, 'toggleLike'])->name('like');
    });

    // 🔥 USER LIKES
    Route::get('/user-likes', function() {
        return response()->json([
            'liked_epreuves' => Auth::user()->likes()->pluck('epreuve_id')
        ]);
    })->name('user.likes');

    // Favoris
    Route::post('/favori/{epreuve}', [FavoriController::class, 'toggle'])->name('favori.toggle');

    // 🔥 CLASSEMENT PUBLIC (TOUS les users)
    Route::get('/classement', [RankingController::class, 'classement'])->name('classement');

    // Profils établissements
    Route::get('/etablissement/{user}', [AdminController::class, 'profilEtablissement'])->name('etablissement.profil');
    Route::middleware('role:etablissement')->group(function () {
        Route::get('/etablissement/{user}/edit', [AdminController::class, 'editProfilEtablissement'])->name('etablissement.edit');
        Route::put('/etablissement/{user}', [AdminController::class, 'updateProfilEtablissement'])->name('etablissement.update');
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// 🔥 ADMIN (protégé) - TOUT centralisé
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [RankingController::class, 'dashboard'])->name('dashboard');
    Route::get('/classement', [RankingController::class, 'adminClassement'])->name('classement'); // Admin complet
    Route::get('/etablissements', [AdminController::class, 'etablissements'])->name('etablissements');
    Route::get('/epreuves', [AdminController::class, 'epreuves'])->name('epreuves');
    
    Route::post('/valider/{id}', [AdminController::class, 'valider'])->name('valider');
    Route::delete('/decertifier/{id}', [AdminController::class, 'decertifier'])->name('decertifier');
    Route::get('/notifications/check', [AdminController::class, 'checkNotifications'])->name('notifications.check');
    Route::post('/notifications/read', [AdminController::class, 'markNotificationsAsRead'])->name('notifications.read');
});