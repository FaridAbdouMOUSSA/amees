<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🛠 Dashboard Admin - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-hover:hover { transform: translateY(-5px); transition: all 0.3s ease; box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; }
        .stats-badge { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
        .sidebar-sticky { position: sticky; top: 100px; z-index: 10; }
        .navbar-brand { transition: all 0.3s ease; }
        .navbar-brand:hover { transform: scale(1.05); }
        .list-group-item-action:hover { background-color: rgba(0,123,255,.1); }
        .status-badge { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-cogs me-2"></i>🛠 ADMIN AMEES
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="{{ route('epreuves.index') }}">
                <i class="fas fa-file-pdf me-1"></i>Épreuves
            </a>
            <a class="nav-link" href="{{ route('classement') }}">
                <i class="fas fa-trophy me-1"></i>Classement
            </a>
            <a class="nav-link" href="{{ route('admin.etablissements') }}">
                <i class="fas fa-school me-1"></i>Établissements
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn btn-outline-light px-3" title="Déconnexion">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3">
            <div class="card sidebar-sticky card-hover">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistiques rapides</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('epreuves.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                            <span><i class="fas fa-file-pdf me-2 text-primary"></i>📚 Épreuves total</span>
                            <strong class="text-primary">{{ number_format($stats['total_epreuves']) }}</strong>
                        </a>
                        <a href="{{ route('admin.etablissements') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                            <span><i class="fas fa-school me-2 text-success"></i>🏫 Établissements</span>
                            <strong class="text-success">{{ number_format($stats['total_etablissements']) }}</strong>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between">
                            <span><i class="fas fa-check-circle me-2 text-warning"></i>✔ Certifiés</span>
                            <strong class="text-warning">{{ number_format($stats['total_certifies']) }}</strong>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between bg-success text-white">
                            <span><i class="fas fa-download me-2"></i>📥 Downloads</span>
                            <strong class="stats-badge">{{ number_format($stats['total_downloads']) }}</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-9">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">
                        <i class="fas fa-tachometer-alt me-3 text-primary"></i>
                        Dashboard Admin
                    </h1>
                    <small class="text-muted" id="liveDateTime">Bonjour Admin • --/--/---- à --:--</small>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button id="notificationBtn" class="btn btn-outline-info dropdown-toggle position-relative" type="button" data-bs-toggle="dropdown" title="Notifications">
                            <i class="fas fa-bell me-1"></i>
                            <span id="notificationCount" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                                style="display: {{ ($stats['nouveaux_aujourdhui'] ?? 0) > 0 ? 'block' : 'none' }};">
                                {{ $stats['nouveaux_aujourdhui'] ?? 0 }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                            <li><h6 class="dropdown-header">🔔 Notifications ({{ $stats['nouveaux_aujourdhui_reel'] ?? 0 }}/jour)</h6></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-file-pdf text-primary me-2"></i>
                                🆕 {{ $stats['nouveaux_aujourdhui_reel'] ?? 0 }} nouvelles épreuves aujourd'hui
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-download text-success me-2"></i>
                                📈 {{ number_format($stats['total_downloads']) }} downloads total
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a id="markReadBtn" class="dropdown-item text-success fw-bold" href="#">
                                    <i class="fas fa-check-double me-2"></i>
                                    Marquer comme lue
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.etablissements') }}" class="btn btn-outline-primary">
                            <i class="fas fa-school me-1"></i>Établissements
                        </a>
                        <a href="{{ route('admin.classement') }}" class="btn btn-outline-success">
                            <i class="fas fa-trophy me-1"></i>Classement
                        </a>
                    </div>
                </div>
            </div>

            <!-- CARDS STATS -->
            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow h-100 card-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-1">📚 Épreuves totales</h6>
                                    <h3 class="text-primary mb-0">{{ number_format($stats['total_epreuves']) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-pdf fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow h-100 card-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-1">🏫 Établissements</h6>
                                    <h3 class="text-success mb-0">{{ number_format($stats['total_etablissements']) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-school fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow h-100 card-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-1">✔ Certifiés</h6>
                                    <h3 class="text-warning mb-0">{{ number_format($stats['total_certifies']) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow h-100 card-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-1">📥 Total downloads</h6>
                                    <h3 class="text-info mb-0">{{ number_format($stats['total_downloads']) }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-download fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOP ÉTABLISSEMENTS -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-lg border-0 card-hover">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy me-2"></i>
                                Top 5 Établissements actifs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>🏫 Établissement</th>
                                            <th>📚 Épreuves</th>
                                            <th>📊 Score</th>
                                            <th>✅ Statut</th>
                                            <th>⚡ Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($top_etablissements as $user)
                                            <tr class="table-hover">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-school text-primary me-2 fs-5"></i>
                                                        <strong>{{ $user->name }}</strong>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info fs-6">{{ $user->epreuves_count }}</span></td>
                                                <td><strong class="text-success">{{ $user->epreuves_count * 10 }} pts</strong></td>
                                                <td>
                                                    @if($user->certifie)
                                                        <span class="badge bg-success status-badge">
                                                            <i class="fas fa-check-circle me-1"></i>Certifié
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark status-badge">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        @if($user->certifie)
                                                            <form method="POST" action="{{ route('admin.decertifier', $user->id) }}" class="d-inline" 
                                                                onsubmit="return confirm('Décertifier {{ $user->name }} ?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Décertifier">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('admin.valider', $user->id) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" title="Certifier">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <a href="{{ route('etablissement.profil', $user) }}" class="btn btn-outline-primary btn-sm ms-1" title="Voir profil">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i><br>
                                                    Aucun établissement
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTIVITÉ RÉCENTE - FIXÉ ✅ -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 card-hover">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Activité récente 
                                <span class="badge bg-light text-success ms-2">
                                    {{ $stats['nouveaux_aujourdhui_reel'] ?? 0 }} aujourd'hui
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                @forelse($activite_recente as $epreuve)
                                    <a href="{{ route('epreuves.download', $epreuve) }}" class="list-group-item list-group-item-action px-4 py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 text-truncate" style="max-width: 300px;">{{ $epreuve->titre }}</h6>
                                            <small class="text-muted">{{ $epreuve->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2 text-muted small">
                                            <i class="fas fa-school me-1"></i>{{ $epreuve->classe }} • 
                                            <i class="fas fa-book me-1"></i>{{ $epreuve->matiere }} • 
                                            <span class="badge bg-primary">{{ $epreuve->type_epreuve }}</span>
                                        </p>
                                        <small class="text-success fw-bold">
                                            <i class="fas fa-download me-1"></i>Par {{ $epreuve->user->name ?? $epreuve->etablissement->name ?? 'Anonyme' }}
                                        </small>
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted py-5">
                                        <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                                        <h6>Aucune activité récente</h6>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Axios pour AJAX -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🚀 SCRIPT NOTIFICATIONS + HEURE EN TEMPS RÉEL -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    
    let notificationCount = {{ $stats['nouveaux_aujourdhui'] ?? 0 }};
    
    // ✅ HEURE EN TEMPS RÉEL DE LA MACHINE (mise à jour toutes les 30 secondes, sans secondes)
    function updateLiveDateTime() {
        const now = new Date();
        const dateTimeString = `Bonjour Admin • ${now.toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'})} à ${now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit', hour12: false})}`;
        document.getElementById('liveDateTime').textContent = dateTimeString;
    }
    
    // Initialisation + mise à jour toutes les 30 secondes
    updateLiveDateTime();
    setInterval(updateLiveDateTime, 30000);
    
    function updateNotificationBadge(count) {
        const countBadge = document.getElementById('notificationCount');
        if (count > 0) {
            countBadge.style.display = 'block';
            countBadge.textContent = count;
            countBadge.classList.add('bg-danger');
        } else {
            countBadge.style.display = 'none';
        }
    }
    
    async function markAsRead() {
        const btn = document.getElementById('markReadBtn');
        const originalText = btn.innerHTML;
        
        try {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Marquage...';
            btn.disabled = true;
            
            const response = await fetch('{{ route("admin.notifications.read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notifications_read: true })
            });
            
            const data = await response.json();
            
            if (data.success) {
                notificationCount = 0;
                updateNotificationBadge(0);
                btn.innerHTML = '<i class="fas fa-check-double me-2 text-success"></i>Toutes lues !';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 3000);
            }
        } catch (error) {
            console.error('Erreur:', error);
            btn.innerHTML = '<i class="fas fa-exclamation-triangle me-2 text-danger"></i>Erreur';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        }
    }
    
    // Event
    document.getElementById('markReadBtn').addEventListener('click', function(e) {
        e.preventDefault();
        markAsRead();
    });
    
    updateNotificationBadge(notificationCount);
    
    // 🔥 AUTO-REFRESH STATS (toutes les 30s) - DÉTECTE NOUVELLES ÉPREUVES ✅
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("admin.notifications.check") }}');
            const data = await response.json();
            if (data.nouveaux > notificationCount && !data.read) {
                notificationCount = data.nouveaux;
                updateNotificationBadge(notificationCount);
                // Notification sonore optionnelle
                if (notificationCount > 0) {
                    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAo');
                    audio.play().catch(() => {});
                }
            }
        } catch (e) {
            console.log('Auto-refresh échoué');
        }
    }, 30000);
});
</script>

</body>
</html>