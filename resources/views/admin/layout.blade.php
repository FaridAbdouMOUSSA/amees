<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin AMEES')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d47a1;
            --gradient: linear-gradient(135deg, #0d47a1, #1976d2);
        }
        .navbar-admin {
            background: var(--gradient) !important;
        }
        .brand-amees {
            color: #ffd700;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .nav-btn {
            transition: all 0.3s ease;
        }
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR PROPRE -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-lg navbar-admin">
    <div class="container-fluid px-5">
        <!-- PARTIE GAUCHE - Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 me-5" href="{{ route('admin.dashboard') }}">
            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fas fa-graduation-cap text-dark fs-4"></i>
            </div>
            <div>
                <span class="brand-amees fs-3">AMEES</span>
                <span class="d-block" style="font-size:0.75rem; opacity:0.9;">Administration</span>
            </div>
        </a>

        <!-- BOUTONS NAVIGATION (Centre) -->
        <div class="d-flex gap-3 mx-auto">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light rounded-pill px-4 nav-btn">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="{{ route('epreuves.index') }}" class="btn btn-outline-light rounded-pill px-4 nav-btn">
                <i class="fas fa-file-pdf me-2"></i> Épreuves
            </a>
        </div>

        <!-- PARTIE DROITE - Utilisateur -->
        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Str::limit(auth()->user()->name ?? 'Admin', 18) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- CONTENU DES PAGES -->
<main class="py-4">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>