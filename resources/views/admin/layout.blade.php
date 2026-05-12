<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin AMEES')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
<body class="bg-light">
    {{-- 🔔 NOTIFICATIONS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed" 
         style="top: 80px; right: 20px; z-index: 9999; min-width: 350px;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed" 
         style="top: 80px; right: 20px; z-index: 9999; min-width: 350px;" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show position-fixed" 
         style="top: 80px; right: 20px; z-index: 9999; min-width: 350px;" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- NAVBAR -->    
<!-- NAVBAR ADMIN (identique au dashboard) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-cogs me-2"></i>🛠 ADMIN AMEES
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('classement') ? 'active' : '' }}" href="{{ route('classement') }}">
                <i class="fas fa-trophy me-1"></i>Classement
            </a>
            <a class="nav-link {{ request()->routeIs('epreuves.*') ? 'active' : '' }}" href="{{ route('epreuves.index') }}">
                <i class="fas fa-file-pdf me-1"></i>Épreuves
            </a>
            <a class="nav-link {{ request()->routeIs('admin.etablissements') ? 'active' : '' }}" href="{{ route('admin.etablissements') }}">
                <i class="fas fa-school me-1"></i>Établissements
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="nav-link btn btn-outline-light">🚪 Déconnexion</button>
            </form>
        </div>
    </div>
</nav>

<!-- CONTENU PRINCIPAL -->
<main class="container-fluid py-4">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>