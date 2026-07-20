<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏆 Classement - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d47a1;
            --primary-gradient: linear-gradient(135deg, #0d47a1, #1976d2);
        }

        .navbar {
            background: var(--primary-gradient) !important;
        }

        body {
            background: #f8f9fa;
        }

        .classement-card {
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .classement-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .rank-badge {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 900;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .top-1 { background: linear-gradient(45deg, #ffd700, #ffeb3b); color: #000; }
        .top-2 { background: linear-gradient(45deg, #c0c0c0, #e0e0e0); color: #000; }
        .top-3 { background: linear-gradient(45deg, #cd7f32, #deb887); color: #fff; }
        .top-other { background: #0d47a1; color: white; }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.08);
        }

        .score {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('epreuves.index') }}">
            <i class="fas fa-arrow-left me-1"></i>
            <span>AMEES</span>
        </a>
        <div class="navbar-text fs-5 d-flex align-items-center gap-2">
            <i class="fas fa-trophy text-warning"></i>
            Classement Général
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-dark">
            <i class="fas fa-crown text-warning me-3"></i>
            TOP ÉTABLISSEMENTS
        </h1>
        <p class="lead text-muted">Classement basé sur l’activité et la contribution à la communauté</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            @forelse($users as $index => $user)
            <div class="card classement-card mb-4">
                <div class="card-header d-flex align-items-center {{ $index < 3 ? 'bg-white' : 'bg-light' }}">
                    <div class="me-4">
                        <div class="rank-badge {{ $index === 0 ? 'top-1' : ($index === 1 ? 'top-2' : ($index === 2 ? 'top-3' : 'top-other')) }}">
                            {{ $index + 1 }}
                        </div>
                    </div>
                    
                    <div class="flex-grow-1">
                        <h4 class="mb-1 fw-bold text-dark">
                            <i class="fas fa-school me-2 text-primary"></i> 
                            {{ $user->name }}
                        </h4>
                        @if($user->certifie)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Certifié
                            </span>
                        @endif
                    </div>

                    <div class="text-end">
                        <div class="score">{{ number_format($user->score) }} <small class="fs-5">pts</small></div>
                        <small class="text-muted">{{ $user->epreuves_count }} épreuves publiées</small>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-md-4">
                            <i class="fas fa-file-pdf stat-icon text-primary"></i>
                            <h4 class="mb-0 fw-bold">{{ $user->epreuves_count }}</h4>
                            <small class="text-muted">Épreuves</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-download stat-icon text-success"></i>
                            <h4 class="mb-0 fw-bold">{{ number_format($user->epreuves_sum_downloads ?? 0) }}</h4>
                            <small class="text-muted">Téléchargements</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-heart stat-icon text-danger"></i>
                            <h4 class="mb-0 fw-bold">{{ $user->total_likes ?? 0 }}</h4>
                            <small class="text-muted">Likes reçus</small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-trophy fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Aucun classement disponible pour le moment</h3>
                <a href="{{ route('epreuves.create') }}" class="btn btn-primary btn-lg mt-4 rounded-pill px-5">
                    <i class="fas fa-plus me-2"></i>Publier la première épreuve
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>