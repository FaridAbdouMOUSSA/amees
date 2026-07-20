<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMEES - Épreuves & Examens du Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d47a1;
            --primary-gradient: linear-gradient(135deg, #0d47a1, #1976d2);
        }
        .hero {
            background: var(--primary-gradient);
            color: white;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(transparent, #f8f9fa);
        }
        .navbar {
            background: rgba(13, 71, 161, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .feature-card {
            transition: all 0.3s ease;
            border: none;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary);
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="fas fa-graduation-cap"></i> AMEES
        </a>
        
        <div class="ms-auto d-flex align-items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-light">Inscription</a>
            @else
                <a href="{{ route('epreuves.index') }}" class="btn btn-light">
                    <i class="fas fa-book"></i> Épreuves
                </a>
            @endguest
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">
            La plus grande plateforme<br>d'épreuves du Bénin
        </h1>
        <p class="lead fs-4 mb-5 opacity-90">
            Des milliers de sujets d'examens, devoirs et examens blancs<br>
            partagés par les établissements scolaires.
        </p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold">
                <i class="fas fa-user-plus me-2"></i> Rejoindre gratuitement
            </a>
            <a href="{{ route('epreuves.index') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">
                <i class="fas fa-search me-2"></i> Explorer les épreuves
            </a>
        </div>

        <div class="mt-5">
            <img src="https://via.placeholder.com/800x400/ffffff/0d47a1?text=Épreuves+du+Bénin" 
                 class="img-fluid rounded shadow" alt="AMEES Platform" style="max-height: 420px;">
        </div>
    </div>
</section>

<!-- STATISTIQUES -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="stat-number">1200+</div>
                <p class="text-muted">Épreuves disponibles</p>
            </div>
            <div class="col-md-4">
                <div class="stat-number">85</div>
                <p class="text-muted">Établissements actifs</p>
            </div>
            <div class="col-md-4">
                <div class="stat-number">45k</div>
                <p class="text-muted">Téléchargements</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Pourquoi choisir AMEES ?</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card card h-100 p-4 text-center">
                    <div class="mb-4">
                        <i class="fas fa-file-pdf fa-3x text-primary"></i>
                    </div>
                    <h4>Accès aux épreuves</h4>
                    <p class="text-muted">Devoirs, Examens Blancs, BAC et plus encore, classés par classe, matière et série.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card card h-100 p-4 text-center">
                    <div class="mb-4">
                        <i class="fas fa-school fa-3x text-success"></i>
                    </div>
                    <h4>Pour les établissements</h4>
                    <p class="text-muted">Publiez facilement vos épreuves et gagnez en visibilité dans le classement national.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card card h-100 p-4 text-center">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-3x text-warning"></i>
                    </div>
                    <h4>Classement national</h4>
                    <p class="text-muted">Découvrez les meilleurs établissements du Bénin par nombre d'épreuves publiées.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h2 class="fw-bold mb-3">Prêt à commencer ?</h2>
        <p class="lead mb-4">Rejoignez des milliers d'élèves et d'enseignants sur AMEES</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
            Créer mon compte gratuitement
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light py-5">
    <div class="container text-center">
        <p class="mb-1">&copy; {{ date('Y') }} AMEES - Plateforme éducative béninoise</p>
        <p class="small text-muted">Tous droits réservés</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>