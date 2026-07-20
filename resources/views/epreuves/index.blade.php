<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📚 Épreuves - AMEES</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.hover-shadow:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important;
    transform: translateY(-5px);
    transition: all 0.3s ease;
}
.serie-badge { background-color: #6f42c1 !important; }
.navbar-brand span.brand-amees {
    color: #ffd700;
    font-weight: 900;
    letter-spacing: 2px;
}
.navbar-brand span.brand-sub {
    font-size: 0.55em;
    color: #cce5ff;
    display: block;
    letter-spacing: 1px;
    line-height: 1;
}
.nav-pill-link {
    border-radius: 20px !important;
    padding: 6px 14px !important;
    transition: background 0.2s ease;
}
.nav-pill-link:hover {
    background: rgba(255,255,255,0.15) !important;
}
.card-epreuve {
    border-radius: 14px !important;
    transition: all 0.3s ease;
}
.card-epreuve:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    transform: translateY(-4px);
}
.badge-serie {
    background: linear-gradient(135deg, #6f42c1, #9b59b6);
    color: white;
    font-size: 0.75em;
    padding: 4px 8px;
    border-radius: 8px;
}
.score-bar {
    background: linear-gradient(90deg, #28a745, #20c997);
    color: white;
    border-radius: 8px;
    padding: 4px 10px;
}
#loadMoreBtn {
    min-width: 220px;
}
</style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background: linear-gradient(135deg, #0d47a1, #1976d2);">
<div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="fas fa-graduation-cap text-dark"></i>
        </div>
        <div>
            <span class="brand-amees">AMEES</span>
            <span class="brand-sub">Épreuves du Bénin</span>
        </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav ms-auto align-items-center gap-1">
            <li class="nav-item">
                <a class="nav-link nav-pill-link" href="{{ route('epreuves.index') }}">📚 Épreuves</a>
            </li>
            @auth
            <li class="nav-item">
                <a class="nav-link nav-pill-link" href="{{ route('classement') }}">🏆 Classement</a>
            </li>
            @if(auth()->user()->role === 'etablissement')
            <li class="nav-item">
                <a class="btn btn-warning text-dark fw-bold px-3 py-1 rounded-pill ms-1" href="{{ route('epreuves.create') }}">
                    <i class="fas fa-plus me-1"></i>Publier
                </a>
            </li>
            @endif
            @if(auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link nav-pill-link text-warning" href="{{ route('admin.dashboard') }}">👑 Admin</a>
            </li>
            @endif
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 nav-pill-link" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                        <i class="fas fa-user text-dark" style="font-size:13px;"></i>
                    </div>
                    <span>{{ Str::limit(auth()->user()->name, 15) }}</span>
                    @if(auth()->user()->certifie)
                    <span class="badge bg-success" style="font-size:10px;">✔</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    @if(auth()->user()->role === 'etablissement')
                    <li>
                        <a class="dropdown-item" href="{{ route('etablissement.profil', auth()->user()->id) }}">
                            <i class="fas fa-school me-2 text-primary"></i>Mon profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="btn btn-outline-light rounded-pill px-3" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>Connexion
                </a>
            </li>
            @endauth
        </ul>
    </div>
</div>
</nav>

<!-- HERO -->
<div style="background: linear-gradient(135deg, #0d47a1, #1976d2); color:white;" class="py-4 mb-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:1.8rem;">📚 Toutes les épreuves</h1>
            <p class="mb-0 opacity-75">Retrouvez les devoirs et examens blancs des établissements du Bénin</p>
        </div>
        @auth
        @if(auth()->user()->role === 'etablissement')
        <a href="{{ route('epreuves.create') }}" class="btn btn-warning fw-bold rounded-pill px-4">
            <i class="fas fa-plus-circle me-2"></i>Publier une épreuve
        </a>
        @endif
        @endauth
    </div>
</div>
</div>

<!-- FILTRES -->
<div class="container mb-4">
<div class="card border-0 shadow-sm rounded-3">
<div class="card-body py-3">
<form method="GET" action="{{ route('epreuves.search') }}" class="row g-2 align-items-end">
    <!-- Année -->
    <div class="col-md-2">
        <label class="form-label fw-bold small text-muted">📅 Année</label>
        <select name="annee" class="form-select form-select-sm">
            <option value="">Toutes les années</option>
            @php $currentYear = now()->year; @endphp
            @for($year = $currentYear; $year >= 2020; $year--)
            <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endfor
        </select>
    </div>
    <!-- Matière -->
    <div class="col-md-2">
        <label class="form-label fw-bold small text-muted">📖 Matière</label>
        <select name="matiere" class="form-select form-select-sm">
            <option value="">Toutes</option>
            <option value="Mathématiques" {{ request('matiere') == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
            <option value="Français" {{ request('matiere') == 'Français' ? 'selected' : '' }}>Français</option>
            <option value="Histoire-Géo" {{ request('matiere') == 'Histoire-Géo' ? 'selected' : '' }}>Histoire-Géo</option>
            <option value="SVT" {{ request('matiere') == 'SVT' ? 'selected' : '' }}>SVT</option>
            <option value="Physique-Chimie" {{ request('matiere') == 'Physique-Chimie' ? 'selected' : '' }}>Physique-Chimie</option>
            <option value="Anglais" {{ request('matiere') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
            <option value="Espagnol" {{ request('matiere') == 'Espagnol' ? 'selected' : '' }}>Espagnol</option>
            <option value="Philosophie" {{ request('matiere') == 'Philosophie' ? 'selected' : '' }}>Philosophie</option>
        </select>
    </div>
    <!-- Classe -->
    <div class="col-md-2">
        <label class="form-label fw-bold small text-muted">📚 Classe</label>
        <select name="classe" id="filterClasse" class="form-select form-select-sm" onchange="toggleFilters()">
            <option value="">Toutes</option>
            <option value="3ème" {{ request('classe') == '3ème' ? 'selected' : '' }}>3ème</option>
            <option value="1ère" {{ request('classe') == '1ère' ? 'selected' : '' }}>1ère</option>
            <option value="Terminale" {{ request('classe') == 'Terminale' ? 'selected' : '' }}>Terminale</option>
        </select>
    </div>
    <!-- Série -->
    <div class="col-md-2" id="serieFilterField">
        <label class="form-label fw-bold small text-muted">🎓 Série</label>
        <select name="serie" class="form-select form-select-sm">
            <option value="">Toutes</option>
            <option value="A" {{ request('serie') == 'A' ? 'selected' : '' }}>Série A</option>
            <option value="B" {{ request('serie') == 'B' ? 'selected' : '' }}>Série B</option>
            <option value="C" {{ request('serie') == 'C' ? 'selected' : '' }}>Série C</option>
            <option value="D" {{ request('serie') == 'D' ? 'selected' : '' }}>Série D</option>
        </select>
    </div>
    <!-- Type -->
    <div class="col-md-2" id="typeFilterField">
        <label class="form-label fw-bold small text-muted">🎯 Type</label>
        <select name="type_epreuve" id="filterType" class="form-select form-select-sm" onchange="toggleFilters()">
            <option value="">Tous</option>
            <option value="Devoir" {{ request('type_epreuve') == 'Devoir' ? 'selected' : '' }}>Devoir</option>
            <option value="Examen Blanc" {{ request('type_epreuve') == 'Examen Blanc' ? 'selected' : '' }}>Examen Blanc</option>
        </select>
    </div>
    <!-- Semestre -->
    <div class="col-md-1" id="semestreFilterField">
        <label class="form-label fw-bold small text-muted">📅 Sem.</label>
        <select name="semestre" class="form-select form-select-sm">
            <option value="">Tous</option>
            <option value="S1" {{ request('semestre') == 'S1' ? 'selected' : '' }}>S1</option>
            <option value="S2" {{ request('semestre') == 'S2' ? 'selected' : '' }}>S2</option>
        </select>
    </div>
    <!-- Bouton Filtrer -->
    <div class="col-md-3">
        <label class="form-label fw-bold small text-muted">&nbsp;</label>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="fas fa-search me-1"></i>Filtrer
            </button>
            @if(request()->hasAny(['annee','matiere','classe','type_epreuve','semestre','serie']))
            <a href="{{ route('epreuves.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </div>
    </div>
</form>
</div>
</div>
</div>

<!-- CARTES -->
<div class="container">
<div class="row g-4" id="epreuvesContainer">
    @forelse($epreuves as $epreuve)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 card-epreuve">
            <!-- Contenu des cartes (identique à ton code original) -->
            <div class="card-header bg-white border-bottom px-3 py-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:32px;height:32px;min-width:32px;">
                            <i class="fas fa-school text-white" style="font-size:13px;"></i>
                        </div>
                        @if($epreuve->user)
                        <a href="{{ route('etablissement.profil', $epreuve->user) }}" class="text-decoration-none fw-bold text-primary small" style="line-height:1.2;">
                            {{ Str::limit($epreuve->user->name, 22) }}
                        </a>
                        @else
                        <span class="fw-bold text-muted small">Anonyme</span>
                        @endif
                    </div>
                    @if($epreuve->user && $epreuve->user->certifie)
                    <span class="badge bg-success" style="font-size:10px;">✔ Certifié</span>
                    @endif
                </div>
            </div>
            <div class="card-body px-3 py-3">
                <h6 class="fw-bold mb-2">{{ $epreuve->titre }}</h6>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge bg-primary"><i class="fas fa-book me-1"></i>{{ $epreuve->matiere }}</span>
                    <span class="badge bg-info text-dark">{{ $epreuve->classe }}</span>
                    @if($epreuve->classe !== '3ème' && !empty($epreuve->serie) && $epreuve->serie !== 'NULL' && trim($epreuve->serie) !== '')
                    <span class="badge badge-serie"><i class="fas fa-layer-group me-1"></i>Série {{ $epreuve->serie }}</span>
                    @endif
                    @if($epreuve->classe === '1ère')
                    <span class="badge bg-warning text-dark">Devoir</span>
                    @else
                    <span class="badge bg-warning text-dark">{{ $epreuve->type_epreuve ?: ($epreuve->type ?: 'Épreuve') }}</span>
                    @endif
                    @if($epreuve->semestre && $epreuve->type_epreuve !== 'Examen Blanc')
                    <span class="badge bg-secondary">{{ $epreuve->semestre }}</span>
                    @endif
                </div>
                @if($epreuve->description)
                <p class="small text-muted mb-2">{{ Str::limit($epreuve->description, 90) }}</p>
                @endif
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <p class="small text-muted mb-0">
                        <i class="fas fa-calendar me-1"></i>{{ $epreuve->created_at->format('d/m/Y') }}
                    </p>
                    <a href="{{ route('epreuves.show', $epreuve) }}" class="btn btn-outline-info btn-sm py-1 px-3">
                        <i class="fas fa-eye me-1"></i>Voir
                    </a>
                </div>
            </div>
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex gap-2 mb-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill share-btn"
                            data-share-url="<?php echo e(route('epreuves.show', $epreuve)); ?>"
                            onclick="copyShareLink(this); return false;">
                        <i class="fas fa-share-alt me-1"></i>Partager
                    </button>
                    <a href="{{ route('epreuves.download', $epreuve->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i>{{ $epreuve->downloads ?? 0 }}
                    </a>
                    <button type="button"
                            class="btn btn-sm like-toggle {{ in_array($epreuve->id, $likedIds ?? []) ? 'btn-danger' : 'btn-outline-danger' }}"
                            data-id="{{ $epreuve->id }}"
                            title="@if(in_array($epreuve->id, $likedIds ?? []))Je n'aime plus@elseJ'aime @endif">
                        <i class="fas fa-heart me-1"></i>
                        <span class="like-count">{{ $epreuve->likes_count ?? 0 }}</span>
                    </button>
                </div>
                <div class="score-bar text-center" style="font-size:12px;">
                    🏆 Score : {{ (($epreuve->likes_count ?? 0) * 2) + (($epreuve->downloads ?? 0) * 3) }}
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-file-search fa-5x text-muted mb-4"></i>
            <h3 class="text-muted">Aucune épreuve trouvée</h3>
            @auth
            @if(auth()->user()->role === 'etablissement')
            <a href="{{ route('epreuves.create') }}" class="btn btn-success btn-lg mt-3 rounded-pill">
                <i class="fas fa-plus me-2"></i>Être le premier à publier !
            </a>
            @endif
            @endauth
        </div>
    </div>
    @endforelse
</div>

<!-- Bouton Voir plus -->
<div class="text-center mt-5 mb-5" id="loadMoreSection">
    @if($epreuves->hasMorePages())
    <button id="loadMoreBtn" class="btn btn-primary btn-lg px-5 rounded-pill">
        <i class="fas fa-chevron-down me-2"></i>Voir plus d'épreuves
    </button>
    @endif
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Variables globales
let currentPage = {{ $epreuves->currentPage() }};
let lastPage = {{ $epreuves->lastPage() }};
let isLoading = false;

document.addEventListener('DOMContentLoaded', function() {
    toggleFilters();
    initAutoRefresh();
    initLikes();
    initLoadMore();
});

function initLoadMore() {
    const loadBtn = document.getElementById('loadMoreBtn');
    if (loadBtn) loadBtn.addEventListener('click', loadMoreEpreuves);
}

async function loadMoreEpreuves() {
    if (isLoading || currentPage >= lastPage) return;
    isLoading = true;
    const btn = document.getElementById('loadMoreBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Chargement...`;
    currentPage++;

    const url = new URL(window.location.href);
    url.searchParams.set('page', currentPage);

    try {
        const response = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.epreuves_html) {
            document.getElementById('epreuvesContainer').insertAdjacentHTML('beforeend', data.epreuves_html);
            initLikes();
        }
        if (!data.has_more) {
            document.getElementById('loadMoreSection').style.display = 'none';
        }
    } catch (error) {
        console.error('Erreur:', error);
        currentPage--;
    } finally {
        isLoading = false;
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// ====================== FILTRES MIS À JOUR ======================
function toggleFilters() {
    const classe = document.getElementById('filterClasse').value;
    const type = document.getElementById('filterType').value;
    
    const serieField = document.getElementById('serieFilterField');
    const typeField = document.getElementById('typeFilterField');
    const semestreField = document.getElementById('semestreFilterField');

    // 3ème
    if (classe === '3ème') {
        serieField.style.display = 'none';
        serieField.querySelector('select').value = '';
    } else {
        serieField.style.display = 'block';
    }

    // 1ère
    if (classe === '1ère') {
        document.getElementById('filterType').value = 'Devoir';
        typeField.querySelector('select').disabled = true;
    } else {
        typeField.querySelector('select').disabled = false;
    }

    // === NOUVELLE RÈGLE : Terminale + Examen Blanc ===
    if (classe === 'Terminale' && type === 'Examen Blanc') {
        typeField.style.display = 'none';
        semestreField.style.display = 'none';
        semestreField.querySelector('select').value = '';
    } 
    else if (type === 'Examen Blanc') {
        semestreField.style.display = 'none';
        semestreField.querySelector('select').value = '';
        typeField.style.display = 'block';
    } 
    else {
        semestreField.style.display = 'block';
        typeField.style.display = 'block';
    }
}

// ====================== AUTRES FONCTIONS ======================
function copyShareLink(button) {
    const shareUrl = button.getAttribute('data-share-url');
    navigator.clipboard.writeText(shareUrl).then(() => {
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-link me-1"></i>Copié !';
        button.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            button.innerHTML = original;
            button.classList.replace('btn-success', 'btn-outline-primary');
        }, 2500);
    });
}

function initLikes() {
    document.querySelectorAll('.like-toggle').forEach(button => {
        button.onclick = function() { toggleLike(this); };
    });
}

window.toggleLike = function(button) {
    const epreuveId = button.getAttribute('data-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    button.disabled = true;
    fetch(`/epreuves/${epreuveId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.querySelector('.like-count').textContent = data.count || 0;
        if (data.liked) {
            button.classList.remove('btn-outline-danger');
            button.classList.add('btn-danger');
            button.title = "Je n'aime plus";
        } else {
            button.classList.remove('btn-danger');
            button.classList.add('btn-outline-danger');
            button.title = "J'aime";
        }
    })
    .catch(() => button.disabled = false);
};

function initAutoRefresh() {
    // Désactivé pour l'instant
}
</script>
</body>
</html>