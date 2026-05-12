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
.hover-text-dark:hover {
    color: #0d6efd !important;
}
.etablissement-link {
    transition: color 0.2s ease;
}
.etablissement-link:hover {
    color: #0d6efd !important;
    text-decoration: none !important;
}
.serie-badge {
    background-color: #6f42c1 !important;
}
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">
            <i class="fas fa-graduation-cap me-2"></i>AMEES
        </a>
        
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="{{ route('epreuves.index') }}">📚 Épreuves</a>
            @auth
                <a class="nav-link" href="{{ route('classement') }}">🏆 Classement</a>
                @if(auth()->user()->role === 'etablissement')
                    <a class="nav-link btn btn-warning text-dark fw-bold" href="{{ route('epreuves.create') }}">
                        ➕ <i class="fas fa-plus"></i> Publier
                    </a>
                @endif
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    @if(auth()->user()->role === 'etablissement' && auth()->user()->certifie)
                        <span class="badge bg-success ms-1">✔ Certifié</span>
                    @endif
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">🚪 Déconnexion</button>
                </form>
            @else
                <a class="nav-link btn btn-outline-light ms-2" href="{{ route('login') }}">Connexion</a>
            @endauth
        </div>
    </div>
</nav>

@if(auth()->check() && auth()->user()->role === 'admin')

<div class="container mb-3">

    <div class="alert alert-info border-0 shadow-sm">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <i class="fas fa-crown me-2 text-warning"></i>

                <strong>👑 Mode Admin</strong>

            </div>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm">

                <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin

            </a>

        </div>

    </div>

</div>

@endif

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-primary mb-4">
                <i class="fas fa-file-pdf me-3"></i>📚 Toutes les épreuves
            </h1>
            
            @auth
                @if(auth()->user()->role === 'etablissement')
                    <div class="alert alert-success">
                        <i class="fas fa-star me-2"></i>
                        <strong>🏫 ÉTABLISSEMENT :</strong> 
                        <a href="{{ route('epreuves.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Publier une nouvelle épreuve
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

<div class="container mb-4">
    <form method="GET" action="{{ route('epreuves.search') }}" class="row g-3">
        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">📖 Matière</label>
            <select name="matiere" class="form-select">
                <option value="">Toutes matières</option>
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

        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">📚 Classe</label>
            <select name="classe" id="filterClasse" class="form-select" onchange="toggleSerieFilter()">
                <option value="">Toutes classes</option>
                <option value="3ème" {{ request('classe') == '3ème' ? 'selected' : '' }}>3ème</option>
                <option value="1ère" {{ request('classe') == '1ère' ? 'selected' : '' }}>1ère</option>
                <option value="Terminale" {{ request('classe') == 'Terminale' ? 'selected' : '' }}>Terminale</option>
            </select>
        </div>

        <div class="col-md-2 serie-filter-field {{ request('classe') == '3ème' || !request('classe') ? 'd-none' : '' }}" id="serieFilterField">
            <label class="form-label fw-bold small text-muted">🎓 Série</label>
            <select name="serie" class="form-select">
                <option value="">Toutes séries</option>
                <option value="A" {{ request('serie') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ request('serie') == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ request('serie') == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ request('serie') == 'D' ? 'selected' : '' }}>D</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">🎯 Type</label>
            <select name="type_epreuve" class="form-select">
                <option value="">Tous types</option>
                <option value="Devoir" {{ request('type_epreuve') == 'Devoir' ? 'selected' : '' }}>Devoir</option>
                <option value="Examen Blanc" {{ request('type_epreuve') == 'Examen Blanc' ? 'selected' : '' }}>Examen Blanc</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">📅 Semestre</label>
            <select name="semestre" class="form-select">
                <option value="">Tous semestres</option>
                <option value="S1" {{ request('semestre') == 'S1' ? 'selected' : '' }}>S1</option>
                <option value="S2" {{ request('semestre') == 'S2' ? 'selected' : '' }}>S2</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">&nbsp;</label>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
                @if(request('matiere') || request('classe') || request('type_epreuve') || request('semestre') || request('serie'))
                    <a href="{{ route('epreuves.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-2"></i>Tout voir
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="container">
    <div class="row g-4">
        @forelse($epreuves as $epreuve)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <div class="card-header bg-light p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-school fs-4 text-primary me-2"></i>
                            @if($epreuve->user)
                                <a href="{{ route('etablissement.profil', $epreuve->user->id) }}" 
                                   class="text-decoration-none fw-bold text-primary hover-text-dark" 
                                   title="Voir profil {{ $epreuve->user->name }}">
                                    {{ $epreuve->user->name }}
                                </a>
                            @else
                                <span class="fw-bold text-muted">Anonyme</span>
                            @endif
                            @if($epreuve->user && $epreuve->user->certifie)
                                <span class="badge bg-success ms-2">✔ Certifié</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $epreuve->titre }}</h5>
                        <p class="card-text">
                            <i class="fas fa-book me-1 text-primary"></i>{{ $epreuve->matiere }} 
                            <span class="badge bg-info fs-6">{{ $epreuve->classe }}</span>
                            @if($epreuve->serie && in_array($epreuve->classe, ['1ère', 'Terminale']))
                                <span class="badge serie-badge fs-6 ms-1">
                                    <i class="fas fa-layer-group me-1"></i>{{ $epreuve->serie }}
                                </span>
                            @endif
                            <span class="badge bg-warning text-dark ms-1 fs-6">
                                <i class="fas fa-target me-1"></i>
                                {{ $epreuve->type_epreuve ?: ($epreuve->type ?: 'Épreuve') }}
                            </span>
                            @if($epreuve->semestre)
                                <span class="badge bg-secondary ms-1">{{ $epreuve->semestre }}</span>
                            @endif
                        </p>
                        @if($epreuve->description)
                            <p class="small text-muted mt-2">{{ Str::limit($epreuve->description, 100) }}</p>
                        @endif
                        <p class="small text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>{{ $epreuve->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="card-footer bg-transparent pt-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            {{-- 🔗 BOUTON PARTAGER : COPIE LE LIEN DE TÉLÉCHARGEMENT --}}
                            <button class="btn btn-outline-primary btn-sm flex-fill me-1 share-btn" 
                                    data-share-url="{{ route('epreuves.download', $epreuve->id) }}"
                                    title="Copier le lien de téléchargement direct"
                                    onclick="copyShareLink(this); return false;">
                                <i class="fas fa-share-alt me-1"></i>Partager
                            </button>

                            {{-- ⬇️ BOUTON TÉLÉCHARGER : TÉLÉCHARGE DIRECTEMENT --}}
                            <a href="{{ route('epreuves.download', $epreuve->id) }}" 
                               class="btn btn-success btn-sm"
                               title="Télécharger directement">
                                <i class="fas fa-download me-1"></i>{{ $epreuve->downloads ?? 0 }}
                            </a>

                            <div class="like-container" data-epreuve-id="{{ $epreuve->id }}">
                                <button type="button" class="btn like-btn btn-outline-danger btn-sm like-toggle" data-id="{{ $epreuve->id }}">
                                    <i class="fas fa-heart me-1"></i><span class="like-count">{{ $epreuve->likes_count ?? 0 }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-2 p-2 bg-light rounded">
                            <small class="text-success fw-bold">
                                🏆 Score : {{ (($epreuve->likes_count ?? 0) * 2) + (($epreuve->downloads ?? 0) * 3) }}
                            </small>
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
                            <a href="{{ route('epreuves.create') }}" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-plus me-2"></i>Être le premier à publier !
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>

    <div class="row mt-5">
        <div class="col-12">
            {{ $epreuves->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
function toggleSerieFilter() {
    const classe = document.getElementById('filterClasse').value;
    const serieField = document.getElementById('serieFilterField');
    
    if (classe === '3ème') {
        serieField.classList.add('d-none');
        document.querySelector('#serieFilterField select').value = '';
    } else {
        serieField.classList.remove('d-none');
    }
}

// ✅ FONCTION PARTAGE : COPIE LE LIEN DE TÉLÉCHARGEMENT
function copyShareLink(button) {
    const shareUrl = button.getAttribute('data-share-url');
    const titre = button.closest('.card').querySelector('.card-title').textContent;
    
    // Copie l'URL dans le presse-papier
    navigator.clipboard.writeText(shareUrl).then(function() {
        // Feedback visuel amélioré
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-link me-1"></i>Lien DL copié !';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-primary');
        
        // Remettre le texte original après 2.5 secondes
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2500);
        
        // Toast avec le titre de l'épreuve
        showToast(`📥 Lien de téléchargement copié pour "${titre}"`, 'success');
    }).catch(function(err) {
        console.error('Erreur lors de la copie: ', err);
        // Fallback pour anciens navigateurs
        fallbackCopyTextToClipboard(shareUrl, button);
    });
}

// Fallback pour anciens navigateurs
function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-link me-1"></i>Lien DL copié !';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-primary');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }, 2500);
            
            showToast('📋 Lien de téléchargement copié !', 'success');
        }
    } catch (err) {
        console.error('Fallback: Erreur lors de la copie', err);
    }
    
    document.body.removeChild(textArea);
}

// Toast notification
function showToast(message, type = 'info') {
    let toast = document.querySelector('.share-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-' + type + ' border-0 position-fixed top-0 end-0 m-3 share-toast';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
    } else {
        toast.querySelector('.toast-body').textContent = message;
    }
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    // Auto-hide après 4 secondes
    setTimeout(() => bsToast.hide(), 4000);
}

document.addEventListener('DOMContentLoaded', function() {
    checkUserLikes();
    
    document.querySelectorAll('.like-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const epreuveId = this.dataset.id;
            toggleLike(epreuveId, this);
        });
    });
    
    toggleSerieFilter();
});

function checkUserLikes() {
    @auth
        fetch('/user-likes')
            .then(response => response.json())
            .then(data => {
                data.liked_epreuves.forEach(id => {
                    const btn = document.querySelector(`.like-toggle[data-id="${id}"]`);
                    if (btn) markAsLiked(btn);
                });
            });
    @endauth
}

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
        const countSpan = button.querySelector('.like-count');
        countSpan.textContent = data.count;
        
        if (data.liked) {
            markAsLiked(button);
        } else {
            markAsUnliked(button);
        }
    })
    .catch(error => console.error('Erreur like:', error));
}

function markAsLiked(button) {
    button.classList.remove('btn-outline-danger');
    button.classList.add('btn-danger');
    button.style.backgroundColor = '#dc3545';
    button.style.color = 'white';
    button.title = 'Unlike';
}

function markAsUnliked(button) {
    button.classList.remove('btn-danger');
    button.classList.add('btn-outline-danger');
    button.style.backgroundColor = 'transparent';
    button.style.color = '#dc3545';
    button.title = 'Like';
}
</script>

</body>
</html>