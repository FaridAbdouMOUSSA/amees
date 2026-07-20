<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #0d47a1;
            --primary-gradient: linear-gradient(135deg, #0d47a1, #1976d2);
        }
        .navbar { background: var(--primary-gradient) !important; }
        .card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: var(--primary-gradient);
            color: white;
            padding: 3rem 1.5rem 2rem;
        }
        .avatar {
            width: 140px;
            height: 140px;
            border: 6px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            object-fit: cover;
            transition: all 0.3s ease;
        }
        .avatar:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0,0,0,0.4);
        }
        .epreuve-card {
            border-radius: 14px;
            transition: all 0.3s ease;
        }
        .epreuve-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }
        #loadMoreBtn {
            min-width: 220px;
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('epreuves.index') }}">
            <i class="fas fa-arrow-left"></i> Retour aux épreuves
        </a>
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                <i class="fas fa-graduation-cap text-dark"></i>
            </div>
            <span class="text-white fw-bold fs-5">AMEES</span>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card">

                <!-- HEADER PROFIL -->
                <div class="profile-header text-center">
                    <div class="mb-4">
                        @if($user->photo_profil)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#photoModal" title="Cliquez pour agrandir">
                                <img src="{{ Storage::url($user->photo_profil) }}" 
                                     class="avatar rounded-circle" 
                                     alt="{{ $user->name }}"
                                     style="cursor: zoom-in;">
                            </a>
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto"
                                 style="width: 140px; height: 140px; font-size: 3.5rem;">
                                <i class="fas fa-school"></i>
                            </div>
                        @endif
                    </div>

                    <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                    
                    @if($user->certifie)
                    <div class="badge bg-success fs-5 px-4 py-2 mb-3">
                        <i class="fas fa-check-circle me-2"></i>CERTIFIÉ
                    </div>
                    @endif

                    <!-- INFORMATIONS DE CONTACT -->
                    @if($user->commune || $user->telephone || $user->directeur || $user->lien_localisation)
                    <div class="info-box mx-auto" style="max-width: 700px; background: rgba(255,255,255,0.15); border-radius: 12px; padding: 20px;">
                        <div class="row g-4 text-start">
                            @if($user->commune)
                            <div class="col-md-4">
                                <strong>Commune</strong><br>
                                <span class="fs-5">{{ $user->commune }}</span>
                            </div>
                            @endif
                            @if($user->telephone && !empty($user->telephone))
                            <div class="col-md-4">
                                <strong>Téléphone</strong><br>
                                <span class="fs-5">
                                    @php
                                        $phones = $user->telephone;
                                        if (is_string($phones)) {
                                            $phones = json_decode($phones, true) ?? [$phones];
                                        }
                                        if (!is_array($phones)) {
                                            $phones = [$phones];
                                        }
                                        $formattedPhones = array_map(function($phone) {
                                            return is_string($phone) ? trim($phone) : '';
                                        }, $phones);
                                        echo implode('<br>', array_filter($formattedPhones));
                                    @endphp
                                </span>
                            </div>
                            @endif
                            @if($user->directeur)
                            <div class="col-md-4">
                                <strong>Directeur</strong><br>
                                <span class="fs-5">{{ $user->directeur }}</span>
                            </div>
                            @endif
                        </div>
                        @if($user->lien_localisation)
                        <div class="mt-3">
                            <i class="fas fa-map me-2"></i>
                            <a href="{{ $user->lien_localisation }}" target="_blank" class="text-white text-decoration-none fw-bold">
                                Voir sur Google Maps →
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Description -->
                    @if($user->description)
                    <div class="mt-4 mx-auto info-box" style="max-width: 700px; background: rgba(255,255,255,0.15); border-radius: 12px; padding: 20px;">
                        <h5 class="text-white mb-3">
                            <i class="fas fa-info-circle me-2"></i>Description
                        </h5>
                        <p class="mb-0 fst-italic text-white">
                            {{ Str::limit($user->description, 1000) }}
                        </p>
                    </div>
                    @endif

                    @auth
                    @if(auth()->id() === $user->id)
                    <a href="{{ route('etablissement.edit', $user) }}" class="btn btn-light mt-4">
                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                    </a>
                    @endif
                    @endauth
                </div>

                <!-- BODY -->
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0">
                            <i class="fas fa-file-pdf text-primary me-2"></i>
                            Épreuves publiées
                            <span class="badge bg-primary fs-6">{{ $epreuves->total() }}</span>
                        </h4>
                        
                        @auth
                        @if(auth()->id() === $user->id)
                        <a href="{{ route('epreuves.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Publier une épreuve
                        </a>
                        @endif
                        @endauth
                    </div>

                    <!-- Statistiques -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info text-center">
                                <strong>{{ number_format($downloadsTotal ?? 0) }}</strong><br>
                                <i class="fas fa-download"></i> téléchargements au total
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-danger text-center">
                                <strong>{{ number_format($totalLikes ?? 0) }}</strong><br>
                                <i class="fas fa-heart"></i> likes au total
                            </div>
                        </div>
                    </div>

                    <!-- Liste des épreuves -->
                    <div class="row g-4" id="epreuvesContainer">
                        @foreach($epreuves as $epreuve)
                            @include('epreuves.partials.epreuve-card-profil')
                        @endforeach
                    </div>
<!-- Bouton Voir plus -->
<div class="text-center mt-5 mb-5" id="loadMoreSection">
    @if($epreuves->hasMorePages())
        <button id="loadMoreBtn" class="btn btn-primary btn-lg px-5 rounded-pill">
            <i class="fas fa-chevron-down me-2"></i>Voir plus d'épreuves
        </button>
    @endif
</div>

<!-- Message fin -->
<div id="endMessage" class="text-center mt-5 mb-5" style="display: none;">
    <i class="fas fa-check-circle fa-2x text-success"></i>
    <p class="text-muted">Toutes les épreuves ont été chargées</p>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ==================== FONCTIONS GLOBALES ====================

// Initialisation des boutons Like
function initLikes() {
    document.querySelectorAll('.like-toggle').forEach(button => {
        button.onclick = function() {
            toggleLike(this);
        };
    });
}

// Fonction Like (AJAX)
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
    .catch(() => {
        button.disabled = false;
    });
};

// ==================== LOAD MORE ====================
let currentPage = {{ $epreuves->currentPage() }};
let isLoading = false;

document.addEventListener('DOMContentLoaded', function() {
    initLikes();                    // Initialise les likes au chargement
    console.log('✅ Profil chargé - Likes & Load More prêts');
    
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreEpreuves);
    }
});

async function loadMoreEpreuves() {
    if (isLoading) return;
    
    isLoading = true;
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const originalText = loadMoreBtn.innerHTML;
    
    loadMoreBtn.disabled = true;
    loadMoreBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Chargement...`;

    currentPage++;

    try {
        const response = await fetch(`{{ route('etablissement.profil', $user) }}?page=${currentPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Erreur serveur');

        const data = await response.json();

        if (data.epreuves_html) {
            document.getElementById('epreuvesContainer').insertAdjacentHTML('beforeend', data.epreuves_html);
            initLikes();                    // ← Réinitialise les likes sur les nouvelles cartes
        }

        if (!data.has_more) {
            document.getElementById('loadMoreSection').style.display = 'none';
            document.getElementById('endMessage').style.display = 'block';
        }
    } catch (error) {
        console.error('Erreur:', error);
        currentPage--;
        alert("Une erreur s'est produite lors du chargement.");
    } finally {
        isLoading = false;
        loadMoreBtn.disabled = false;
        loadMoreBtn.innerHTML = originalText;
    }
}
</script>

<!-- Modal Photo -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">{{ $user->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0 d-flex align-items-center justify-content-center" style="background: #111;">
                @if($user->photo_profil)
                    <img src="{{ Storage::url($user->photo_profil) }}" 
                         class="img-fluid" 
                         alt="{{ $user->name }}"
                         style="max-height: 92vh; max-width: 100%; object-fit: contain;">
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>