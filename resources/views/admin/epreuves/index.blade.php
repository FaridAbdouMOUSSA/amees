@extends('layouts.app')

@section('title', '📚 Épreuves - AMEES')

@section('content')
<!-- 🔥 CSRF TOKEN (OBLIGATOIRE) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">
                <i class="fas fa-file-alt text-primary me-3"></i>
                Toutes les Épreuves
                <span class="badge bg-primary ms-2">{{ $epreuves->total() }}</span>
            </h1>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm mb-4 p-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('epreuves.index') }}">
                <i class="fas fa-book-open me-2"></i>AMEES
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="{{ route('epreuves.index') }}">
                    <i class="fas fa-list me-1"></i>📚 Épreuves
                </a>
                
                @auth
                    <a class="nav-link" href="{{ route('classement') }}">
                        <i class="fas fa-trophy me-1"></i>🏆 Classement
                    </a>
                    
                    @if(auth()->user()->role === 'etablissement')
                        <a class="nav-link btn btn-warning text-dark fw-bold px-3" href="{{ route('epreuves.create') }}">
                            ➕ <i class="fas fa-plus"></i> Publier
                        </a>
                    @endif
                    
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ auth()->user()->name }}
                        @if(auth()->user()->role === 'etablissement' && auth()->user()->certifie)
                            <span class="badge bg-success ms-2">✔ Certifié</span>
                        @elseif(auth()->user()->role === 'admin')
                            <span class="badge bg-danger ms-2">👑 Admin</span>
                        @endif
                    </span>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FILTRES --}}
    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">🔍 Recherche</label>
                    <input type="text" name="q" class="form-control" 
                           value="{{ request('q') }}" placeholder="Titre, matière, établissement...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">📅 Année</label>
                    <select name="annee" class="form-select">
                        <option value="">Toutes</option>
                        @for($i = now()->year; $i >= 2015; $i--)
                            <option value="{{ $i }}" {{ request('annee') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 mb-3 mb-md-0">
                        <i class="fas fa-search me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- GRILLE ÉPREUVES --}}
    <div class="row g-4">
        @forelse($epreuves as $epreuve)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm hover-shadow transition-all">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1 pe-2">
                            <h6 class="card-title mb-1 fw-bold">{{ Str::limit($epreuve->titre, 50) }}</h6>
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-school text-muted me-1"></i>
                                <small class="text-muted">{{ $epreuve->user->name }}</small>
                                @if($epreuve->user->commune)
                                    <span class="mx-1">•</span>
                                    <small class="text-muted">{{ $epreuve->user->commune }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="text-end ms-2">
                            @if($epreuve->user->certifie)
                                <span class="badge bg-success fs-6 px-2 py-1 mb-1">✅ Certifié</span>
                            @else
                                <span class="badge bg-warning fs-6 px-2 py-1 mb-1">⏳ Attente</span>
                            @endif
                        </div>
                    </div>

                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($epreuve->description, 80) }}
                    </p>

                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <span class="badge bg-info me-1">{{ $epreuve->niveau ?? $epreuve->classe }}</span>
                            <span class="badge bg-secondary">{{ $epreuve->annee }}</span>
                        </div>
                        
                        {{-- 🔥 LIKE AJAX NOUVEAU SYSTÈME --}}
                        <div class="text-end">
                            <div class="like-container mb-1" data-epreuve-id="{{ $epreuve->id }}">
                                <button type="button" 
                                        class="btn like-btn @if(auth()->check() && $epreuve->isLikedByUser()) btn-danger @else btn-outline-danger @endif btn-sm like-toggle px-2 py-1"
                                        data-id="{{ $epreuve->id }}"
                                        @if(auth()->check() && $epreuve->isLikedByUser()) style="background-color: #dc3545 !important; color: white !important; border-color: #dc3545 !important;" @endif
                                        title="@if(auth()->check() && $epreuve->isLikedByUser()) Retirer le like @else Liker @endif">
                                    <i class="fas fa-heart me-1"></i>
                                    <span class="like-count">{{ $epreuve->likes_count }}</span>
                                </button>
                            </div>
                            <small class="text-muted">
                                📥 {{ number_format($epreuve->downloads) }} téléchargements
                            </small>
                        </div>
                    </div>

                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('epreuves.show', $epreuve) }}" 
                           class="btn btn-primary flex-fill" title="Voir/Download">
                            <i class="fas fa-eye me-1"></i> Voir
                        </a>
                        <a href="{{ route('etablissement.profil', $epreuve->user) }}" 
                           class="btn btn-outline-secondary" title="Profil">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 pt-2">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $epreuve->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-file-alt fa-4x text-muted mb-4 opacity-50"></i>
            <h4 class="text-muted mb-3">Aucune épreuve trouvée</h4>
            <p class="text-muted lead">Commencez par filtrer ou attendez que les établissements publient !</p>
            @auth && auth()->user()->role === 'etablissement'
                <a href="{{ route('epreuves.create') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-plus me-2"></i>Publier la première !
                </a>
            @endauth
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($epreuves->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $epreuves->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- 🔥 SCRIPT LIKE AJAX --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des likes AJAX
    document.querySelectorAll('.like-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const epreuveId = this.dataset.id;
            toggleLike(epreuveId, this);
        });
    });
});

function toggleLike(epreuveId, button) {
    fetch(`/epreuves/${epreuveId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Connectez-vous pour liker !');
            return;
        }
        
        const countSpan = button.querySelector('.like-count');
        countSpan.textContent = data.count;
        
        if (data.liked) {
            markAsLiked(button);
        } else {
            markAsUnliked(button);
        }
    })
    .catch(error => {
        console.error('Erreur like:', error);
        alert('Erreur lors du like');
    });
}

function markAsLiked(button) {
    button.classList.remove('btn-outline-danger');
    button.classList.add('btn-danger');
    button.style.backgroundColor = '#dc3545';
    button.style.color = 'white';
    button.style.borderColor = '#dc3545';
    button.title = 'Retirer le like';
}

function markAsUnliked(button) {
    button.classList.remove('btn-danger');
    button.classList.add('btn-outline-danger');
    button.style.backgroundColor = '';
    button.style.color = '';
    button.style.borderColor = '';
    button.title = 'Liker';
}
</script>
@endsection