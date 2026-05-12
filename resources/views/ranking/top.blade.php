<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏆 Classement - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-lg">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('epreuves.index') }}">
            <i class="fas fa-arrow-left me-2"></i>Épreuves
        </a>
        <span class="navbar-text ms-auto">
            <i class="fas fa-trophy me-2"></i>🏆 CLASSement
        </span>
    </div>
</nav>

<!-- HERO -->
<div class="container py-5 text-white text-center">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 fw-bold mb-4 animate-pulse">
                <i class="fas fa-crown me-3"></i>🏆 TOP 10 ÉTABLISSEMENTS
            </h1>
            <p class="lead mb-0">Les meilleurs établissements AMEES</p>
        </div>
    </div>
</div>

<!-- CLASSMENT -->
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @forelse($users as $index => $user)
                <div class="card shadow-lg border-0 mb-4 overflow-hidden classement-card animate-slide-up">
                    <!-- MEDAILLE -->
                    <div class="position-relative">
                        <div class="medaille position-absolute top-0 start-0 p-3 z-3">
                            <div class="medaille-circle {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : ($index === 2 ? 'bg-danger' : 'bg-light')) }} text-dark fw-bold fs-5 shadow">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <!-- HEADER -->
                        <div class="card-header bg-gradient-{{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'primary')) }} text-white py-4 px-5">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-1 fw-bold">
                                        <i class="fas fa-school me-3"></i>{{ $user->name }}
                                    </h2>
                                    @if($user->certifie)
                                        <span class="badge bg-light text-dark fs-6">✔ Certifié</span>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    <h3 class="mb-0 fw-bold score-anim">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        {{ number_format($user->score, 0) }} pts
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STATS -->
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                                    <h4>{{ number_format($user->score) }}</h4>
                                    <small class="text-muted">Score total</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
                                    <h4>{{ $user->epreuves_count }}</h4>
                                    <small class="text-muted">Épreuves</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-trophy fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted">Pas encore de classement</h3>
                    <a href="{{ route('epreuves.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Publier pour monter !
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
.bg-gradient-gold { background: linear-gradient(45deg, #ffd700, #ffed4e); }
.bg-gradient-silver { background: linear-gradient(45deg, #c0c0c0, #e5e5e5); }
.bg-gradient-bronze { background: linear-gradient(45deg, #cd7f32, #deb887); }
.medaille-circle {
    width: 60px; height: 60px; border-radius: 50%; 
    display: flex; align-items: center; justify-content: center;
}
.stat-box h4 { font-size: 2rem; font-weight: bold; color: #333; }
.stat-box { padding: 20px 10px; }
.animate-slide-up { animation: slideUp 0.6s ease; }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.score-anim { animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
.classement-card:nth-child(odd) { transform: translateY(10px); }
.classement-card:nth-child(even) { transform: translateY(-10px); }
</style>
</body>
</html>