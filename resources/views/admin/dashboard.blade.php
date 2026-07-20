<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👑 Dashboard Admin - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { --primary: #0d47a1; --gradient: linear-gradient(135deg, #0d47a1, #1976d2); }
        body { background: #f8f9fa; }
        .navbar-admin { background: var(--gradient) !important; }
        .brand-amees { color: #ffd700; font-weight: 900; letter-spacing: 2px; }
        .stat-card { border-radius: 16px; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important; }
        .hero-admin { background: var(--gradient); color: white; border-radius: 0 0 24px 24px; }
        .ranking-row:hover { background-color: #f8f9fa; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-lg navbar-admin">
    <div class="container-fluid px-5">
        <a class="navbar-brand d-flex align-items-center gap-2 me-5" href="{{ route('admin.dashboard') }}">
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-graduation-cap text-dark fs-4"></i>
            </div>
            <div>
                <span class="brand-amees fs-3">AMEES</span>
                <span class="d-block" style="font-size:0.75rem; opacity:0.9;">Administration</span>
            </div>
        </a>

        <div class="d-flex gap-3 mx-auto">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light rounded-pill px-4 active">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.etablissements') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="fas fa-school me-2"></i> Établissements
            </a>
            <a href="{{ route('epreuves.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="fas fa-file-pdf me-2"></i> Épreuves
            </a>
        </div>

        <div class="ms-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- HERO -->
<div class="hero-admin py-5 text-white">
    <div class="container">
        <h1 class="display-5 fw-bold">Tableau de bord Administrateur</h1>
        <p class="lead opacity-90">Vue d'ensemble complète de la plateforme</p>
    </div>
</div>

<div class="container pb-5">

    <!-- Statistiques -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card card text-white shadow" style="background: var(--gradient);">
                <div class="card-body text-center p-4">
                    <i class="fas fa-file-pdf fa-3x mb-3"></i>
                    <h5>Épreuves Totales</h5>
                    <h2 class="fw-bold">{{ $stats['total_epreuves'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card text-white shadow" style="background: #28a745;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-school fa-3x mb-3"></i>
                    <h5>Établissements</h5>
                    <h2 class="fw-bold">{{ $stats['total_etablissements'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card text-white shadow" style="background: #6f42c1;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h5>Certifiés</h5>
                    <h2 class="fw-bold">{{ $stats['total_certifies'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card text-white shadow" style="background: #fd7e14;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-download fa-3x mb-3"></i>
                    <h5>Téléchargements</h5>
                    <h2 class="fw-bold">{{ number_format($stats['total_downloads'] ?? 0) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
    <div class="stat-card card text-white shadow" style="background: #17a2b8;">
        <div class="card-body text-center p-4">
            <i class="fas fa-users fa-3x mb-3"></i>
            <h5>Élèves Inscrits</h5>
            <h2 class="fw-bold">{{ $stats['total_eleves'] ?? 0 }}</h2>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="stat-card card text-white shadow" style="background: #dc3545;">
        <div class="card-body text-center p-4">
            <i class="fas fa-user-times fa-3x mb-3"></i>
            <h5>Non Certifiés</h5>
            <h2 class="fw-bold">{{ $stats['total_non_certifies'] ?? 0 }}</h2>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="stat-card card text-white shadow" style="background: #6f42c1;">
        <div class="card-body text-center p-4">
            <i class="fas fa-heart fa-3x mb-3"></i>
            <h5>Total Likes</h5>
            <h2 class="fw-bold">{{ number_format($stats['total_likes'] ?? 0) }}</h2>
        </div>
    </div>
</div>
    </div>
<div class="col-lg-12 mt-4">
    <div class="card">
        <div class="card-header bg-white">
            <h5><i class="fas fa-bell me-2"></i>Activité Récente</h5>
        </div>
        <div class="card-body p-0">
            @foreach($activiteRecente as $epreuve)
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $epreuve->titre }}</strong><br>
                    <small class="text-muted">Par {{ $epreuve->user->name }}</small>
                </div>
                <small class="text-muted">{{ $epreuve->created_at->diffForHumans() }}</small>
            </div>
            @endforeach
        </div>
    </div>
</div>
    <div class="row g-4">
        <!-- Graphique Évolution Mensuelle -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5><i class="fas fa-chart-line me-2"></i>Évolution Mensuelle des Épreuves ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <!-- Inscriptions Mensuelles -->
        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5><i class="fas fa-users me-2"></i>Évolution des Inscriptions Mensuelles par Groupe ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="inscriptionsChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Classement Avancé -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5><i class="fas fa-trophy text-warning me-2"></i>Classement des Établissements</h5>
                    
<div class="d-flex gap-2 flex-wrap" id="rankingFilters">
    <!-- Périodes -->
    <div class="btn-group" id="periodButtons">
        <button data-period="all" 
                onclick="filterRanking('all', '{{ $sort }}')"
                class="btn btn-sm {{ $period == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
            Tout
        </button>
        <button data-period="month" 
                onclick="filterRanking('month', '{{ $sort }}')"
                class="btn btn-sm {{ $period == 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
            Mois
        </button>
        <button data-period="quarter" 
                onclick="filterRanking('quarter', '{{ $sort }}')"
                class="btn btn-sm {{ $period == 'quarter' ? 'btn-primary' : 'btn-outline-primary' }}">
            Trimestre
        </button>
        <button data-period="year" 
                onclick="filterRanking('year', '{{ $sort }}')"
                class="btn btn-sm {{ $period == 'year' ? 'btn-primary' : 'btn-outline-primary' }}">
            Année
        </button>
    </div>

    <!-- Tris -->
    <div class="btn-group" id="sortButtons">
        <button data-sort="epreuves" 
                onclick="filterRanking('{{ $period }}', 'epreuves')"
                class="btn btn-sm {{ $sort == 'epreuves' ? 'btn-primary' : 'btn-outline-primary' }}">
            Épreuves
        </button>
        <button data-sort="downloads" 
                onclick="filterRanking('{{ $period }}', 'downloads')"
                class="btn btn-sm {{ $sort == 'downloads' ? 'btn-primary' : 'btn-outline-primary' }}">
            Téléch.
        </button>
        <button data-sort="likes" 
                onclick="filterRanking('{{ $period }}', 'likes')"
                class="btn btn-sm {{ $sort == 'likes' ? 'btn-primary' : 'btn-outline-primary' }}">
            Likes
        </button>
        <button data-sort="score" 
                onclick="filterRanking('{{ $period }}', 'score')"
                class="btn btn-sm {{ $sort == 'score' ? 'btn-primary' : 'btn-outline-primary' }}">
            Score
        </button>
    </div>
</div>
                </div>

                <div class="card-body p-0" id="rankingContainer" style="max-height: 580px; overflow-y: auto;">
                    @forelse($classement as $index => $etab)
                    <div class="ranking-row p-4 border-bottom d-flex align-items-center">
                        <div class="me-3 fw-bold text-muted" style="width:40px;">#{{ $index + 1 }}</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $etab->name }}</h6>
                            @if($etab->certifie)
                                <span class="badge bg-success">✓ Certifié</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary me-2">{{ $etab->epreuves_count }} épreuves</span>
                            <span class="badge bg-info me-2">{{ $etab->total_downloads ?? 0 }} dl</span>
                            <span class="badge bg-danger">{{ $etab->total_likes ?? 0 }} likes</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-5 text-muted">Aucun établissement trouvé.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Répartition par Classe -->
        <div class="col-lg-6 mt-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5><i class="fas fa-chalkboard-teacher me-2"></i>Répartition par Classe</h5>
                </div>
                <div class="card-body">
                    <canvas id="classeChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par Matière -->
        <div class="col-lg-6 mt-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5><i class="fas fa-book me-2"></i>Répartition par Matière</h5>
                </div>
                <div class="card-body">
                    <canvas id="matiereChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ====================== GRAPHES ======================
document.addEventListener('DOMContentLoaded', function () {
    
    // Évolution Mensuelle
    new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Épreuves publiées',
                data: @json($evolutionData ?? array_fill(0,12,0)),
                borderColor: '#0d47a1',
                backgroundColor: 'rgba(13, 71, 161, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { 
            responsive: true, 
            plugins: { legend: { display: true } } 
        }
    });

    // Inscriptions Mensuelles
    new Chart(document.getElementById('inscriptionsChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                { 
                    label: 'Élèves', 
                    data: @json($inscriptionsMensuelles['eleve'] ?? array_fill(0,12,0)), 
                    borderColor: '#28a745', 
                    fill: true 
                },
                { 
                    label: 'Établissements', 
                    data: @json($inscriptionsMensuelles['etablissement'] ?? array_fill(0,12,0)), 
                    borderColor: '#0d47a1', 
                    fill: true 
                }
            ]
        },
        options: { responsive: true }
    });

    // Répartition par Classe
    new Chart(document.getElementById('classeChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($repartitionClasse ?? [])),
            datasets: [{ 
                data: @json(array_values($repartitionClasse ?? [])), 
                backgroundColor: ['#0d47a1','#1976d2','#42a5f5','#90caf9'] 
            }]
        },
        options: { responsive: true }
    });

    // Répartition par Matière
    new Chart(document.getElementById('matiereChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($repartitionMatiere ?? [])),
            datasets: [{ 
                label: "Nombre d'épreuves", 
                data: @json(array_values($repartitionMatiere ?? [])), 
                backgroundColor: '#0d47a1' 
            }]
        },
        options: { 
            responsive: true, 
            indexAxis: 'y' 
        }
    });

});

// ====================== FILTRES CLASSEMENT ======================
let currentPeriod = '{{ $period }}';
let currentSort   = '{{ $sort }}';

function filterRanking(period, sort) {
    currentPeriod = period;
    currentSort   = sort;

    const url = `{{ route('admin.dashboard') }}?period=${period}&sort=${sort}`;
    const container = document.getElementById('rankingContainer');

    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted">Chargement du classement...</p>
        </div>
    `;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => {
        if (!r.ok) throw new Error('Erreur serveur');
        return r.text();
    })
    .then(html => {
        container.innerHTML = html;
        updateActiveButtons();
    })
    .catch(() => {
        container.innerHTML = `<p class="text-center py-5 text-danger">Erreur de chargement.</p>`;
    });
}

function updateActiveButtons() {
    // Périodes
    document.querySelectorAll('#periodButtons button').forEach(btn => {
        const active = btn.dataset.period === currentPeriod;
        btn.classList.toggle('btn-primary', active);
        btn.classList.toggle('btn-outline-primary', !active);
        btn.onclick = () => filterRanking(btn.dataset.period, currentSort);
    });

    // Tris
    document.querySelectorAll('#sortButtons button').forEach(btn => {
        const active = btn.dataset.sort === currentSort;
        btn.classList.toggle('btn-primary', active);
        btn.classList.toggle('btn-outline-primary', !active);
        btn.onclick = () => filterRanking(currentPeriod, btn.dataset.sort);
    });
}

// Initialisation des boutons au chargement
document.addEventListener('DOMContentLoaded', function () {
    updateActiveButtons();
});

// Suppression d'épreuve
function confirmDelete(id, titre) {
    if (confirm(`⚠️ Voulez-vous vraiment supprimer l'épreuve :\n\n"${titre}" ?\n\nCette action est irréversible !`)) {
        
        fetch(`/epreuves/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert("Erreur lors de la suppression.");
            }
        })
        .catch(() => alert("Erreur de connexion."));
    }
}
</script>
</body>
</html>