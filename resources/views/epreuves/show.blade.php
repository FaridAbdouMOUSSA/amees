<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $epreuve->titre }} - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
        .hero-epreuve {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: white;
        }
        .badge-serie {
            background: linear-gradient(135deg, #6f42c1, #9b59b6);
            color: white;
            font-size: 0.85em;
            padding: 6px 12px;
            border-radius: 10px;
        }
        .score-bar {
            background: linear-gradient(90deg, #28a745, #20c997);
            color: white;
            border-radius: 10px;
            padding: 8px 16px;
        }
        .info-card {
            border-radius: 14px;
            border: none;
        }
        .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label-col {
            color: #6c757d;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background: linear-gradient(135deg, #0d47a1, #1976d2);">
    <div class="container">
        
        <!-- Bouton Retour -->
        <a href="{{ 
            request('from') === 'profil' && isset($epreuve->user) 
                ? route('etablissement.profil', $epreuve->user) 
                : route('epreuves.index') 
        }}" 
           class="navbar-brand d-flex align-items-center gap-2 fw-bold">
            <i class="fas fa-arrow-left"></i> 
            Retour {{ request('from') === 'profil' ? 'au Profil' : 'aux Épreuves' }}
        </a>

        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center"
                 style="width:40px;height:40px;">
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
                    <a class="nav-link nav-pill-link" href="{{ route('epreuves.index') }}">
                        📚 Épreuves
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link nav-pill-link" href="{{ route('classement') }}">
                            🏆 Classement
                        </a>
                    </li>

                    @if(auth()->user()->role === 'etablissement')
                        <li class="nav-item">
                            <a class="btn btn-warning text-dark fw-bold px-3 py-1 rounded-pill ms-1"
                               href="{{ route('epreuves.create') }}">
                                <i class="fas fa-plus me-1"></i>Publier
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link nav-pill-link text-warning" href="{{ route('admin.dashboard') }}">
                                👑 Admin
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 nav-pill-link"
                           href="#" role="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                 style="width:30px;height:30px;">
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

{{-- ─────────────── HERO ─────────────── --}}
<div class="hero-epreuve py-4 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="opacity:0.8; font-size:0.85em;">
                <li class="breadcrumb-item">
                    <a href="{{ route('epreuves.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-book me-1"></i>Épreuves
                    </a>
                </li>
                <li class="breadcrumb-item text-white active">{{ Str::limit($epreuve->titre, 40) }}</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-2" style="font-size:1.6rem;">
            {{ $epreuve->titre }}
        </h1>

        {{-- Badges visibles dans le hero --}}
        <div class="d-flex flex-wrap gap-2 mt-2">
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-book me-1"></i>{{ $epreuve->matiere }}
            </span>
            <span class="badge bg-info text-dark fs-6 px-3 py-2">
                <i class="fas fa-user-graduate me-1"></i>{{ $epreuve->classe }}
            </span>

            {{-- ✅ SÉRIE — toujours affichée si présente --}}
            @if($epreuve->serie)
                <span class="badge-serie fs-6 px-3 py-2 d-inline-flex align-items-center">
                    <i class="fas fa-layer-group me-1"></i>Série {{ $epreuve->serie }}
                </span>
            @endif

            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                <i class="fas fa-tag me-1"></i>{{ $epreuve->type_epreuve ?: ($epreuve->type ?: 'Épreuve') }}
            </span>

            @if($epreuve->semestre)
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-calendar me-1"></i>{{ $epreuve->semestre }}
                </span>
            @endif
        </div>
    </div>
</div>

{{-- ─────────────── CONTENU PRINCIPAL ─────────────── --}}
<div class="container mb-5">
    <div class="row g-4">

        {{-- Colonne gauche : infos détaillées --}}
        <div class="col-lg-8">
            <div class="card info-card shadow-sm mb-4">
                <div class="card-body p-4">

                    {{-- Établissement --}}
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-school text-white"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Établissement</div>
                            @if($epreuve->user)
                                <a href="{{ route('etablissement.profil', $epreuve->user->id) }}"
                                   class="fw-bold text-primary text-decoration-none">
                                    {{ $epreuve->user->name }}
                                </a>
                                @if($epreuve->user->certifie)
                                    <span class="badge bg-success ms-2" style="font-size:11px;">✔ Certifié</span>
                                @endif
                            @else
                                <span class="fw-bold text-muted">Anonyme</span>
                            @endif
                        </div>
                    </div>

                    {{-- Matière --}}
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-book text-primary"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Matière</div>
                            <span class="fw-semibold">{{ $epreuve->matiere }}</span>
                        </div>
                    </div>

                    {{-- Classe --}}
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-user-graduate text-info"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Classe</div>
                            <span class="fw-semibold">{{ $epreuve->classe }}</span>
                        </div>
                    </div>

                    {{-- ✅ SÉRIE — ligne dédiée, toujours affichée si présente --}}
                    @if($epreuve->serie)
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;background:rgba(111,66,193,0.12);">
                            <i class="fas fa-layer-group" style="color:#6f42c1;"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Série</div>
                            <span class="fw-bold fs-5" style="color:#6f42c1;">
                                Série {{ $epreuve->serie }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Type --}}
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-tag text-warning"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Type d'épreuve</div>
                            <span class="fw-semibold">
                                {{ $epreuve->type_epreuve ?: ($epreuve->type ?: 'Épreuve') }}
                            </span>
                        </div>
                    </div>

                    {{-- Semestre (seulement si présent) --}}
                    @if($epreuve->semestre)
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-calendar text-secondary"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Semestre</div>
                            <span class="fw-semibold">{{ $epreuve->semestre }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Date de publication --}}
                    <div class="info-row d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-calendar-alt text-success"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Publié le</div>
                            <span class="fw-semibold">{{ $epreuve->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    {{-- Description (si présente) --}}
                    @if($epreuve->description)
                    <div class="info-row d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;">
                            <i class="fas fa-align-left text-muted"></i>
                        </div>
                        <div>
                            <div class="label-col mb-1">Description</div>
                            <p class="mb-0 text-muted">{{ $epreuve->description }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Colonne droite : actions --}}
        <div class="col-lg-4">
            <div class="card info-card shadow-sm mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:0.8em;letter-spacing:1px;">
                        Actions
                    </h6>

                    {{-- Télécharger --}}
                    <a href="{{ route('epreuves.download', $epreuve->id) }}"
                       class="btn btn-success w-100 mb-2 fw-bold">
                        <i class="fas fa-download me-2"></i>Télécharger l'épreuve
                        <span class="badge bg-white text-success ms-2">{{ $epreuve->downloads ?? 0 }}</span>
                    </a>

                    {{-- Like --}}
                    <button type="button"
                            class="btn w-100 mb-2 fw-bold like-toggle {{ in_array($epreuve->id, $likedIds ?? []) ? 'btn-danger' : 'btn-outline-danger' }}"
                            data-id="{{ $epreuve->id }}">
                        <i class="fas fa-heart me-2"></i>
                        {{ in_array($epreuve->id, $likedIds ?? []) ? 'Ne plus aimer' : 'J\'aime' }}
                        <span class="badge bg-white text-danger ms-2 like-count">{{ $epreuve->likes_count ?? $epreuve->likes()->count() }}</span>
                    </button>

                    {{-- Partager --}}
                    <button class="btn btn-outline-primary w-100 share-btn"
                            data-share-url="{{ route('epreuves.show', $epreuve->id) }}"
                            onclick="copyShareLink(this); return false;">
                        <i class="fas fa-share-alt me-2"></i>Copier le lien
                    </button>

                    <hr>

                    {{-- Score --}}
                    <div class="score-bar text-center fw-bold">
                        🏆 Score : {{ (($epreuve->likes_count ?? $epreuve->likes()->count()) * 2) + (($epreuve->downloads ?? 0) * 3) }}
                    </div>
                </div>
            </div>

<!-- Bouton Retour Intelligent -->
<a href="{{ request()->get('from') === 'profil' && $epreuve->user 
    ? route('etablissement.profil', $epreuve->user) 
    : route('epreuves.index') }}" 
   class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white">
    <i class="fas fa-arrow-left"></i> 
    Retour {{ request()->get('from') === 'profil' ? 'au profil' : 'aux épreuves' }}
</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copyShareLink(button) {
    const shareUrl = button.getAttribute('data-share-url');
    navigator.clipboard.writeText(shareUrl).then(() => {
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-link me-2"></i>Lien copié !';
        button.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            button.innerHTML = original;
            button.classList.replace('btn-success', 'btn-outline-primary');
        }, 2500);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = shareUrl;
        ta.style.cssText = 'position:fixed;top:0;left:0;opacity:0;';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try { document.execCommand('copy'); } catch(e) {}
        document.body.removeChild(ta);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`/epreuves/${id}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.status === 401) { window.location.href = '/login'; return; }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                this.querySelector('.like-count').textContent = data.count;
                if (data.liked) {
                    this.classList.replace('btn-outline-danger', 'btn-danger');
                    this.innerHTML = `<i class="fas fa-heart me-2"></i>Ne plus aimer <span class="badge bg-white text-danger ms-2 like-count">${data.count}</span>`;
                } else {
                    this.classList.replace('btn-danger', 'btn-outline-danger');
                    this.innerHTML = `<i class="fas fa-heart me-2"></i>J'aime <span class="badge bg-white text-danger ms-2 like-count">${data.count}</span>`;
                }
            })
            .catch(err => console.error('Erreur like:', err));
        });
    });
});
</script>

</body>
</html>