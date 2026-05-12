<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('epreuves.index') }}">
            <i class="fas fa-arrow-left me-2"></i>Retour épreuves
        </a>
        <a class="navbar-brand fw-bold ms-auto" href="{{ route('home') }}">
            <i class="fas fa-graduation-cap me-2"></i>AMEES
        </a>
    </div>
</nav>

<!-- PROFIL ÉTABLISSEMENT -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                
                <!-- ✅ HEADER PROFIL COMPLET -->
                <div class="card-header bg-gradient-primary text-white text-center py-5 position-relative">
                    <div class="row align-items-end">
                        <div class="col-md-2 text-center mb-3">
                            <img src="{{ $user->photo_profil ? Storage::url($user->photo_profil) : asset('images/ecole-default.jpg') }}" 
                                 class="rounded-circle shadow-lg img-fluid" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-10">
                            <h1 class="display-5 fw-bold mb-2">{{ $user->name }}</h1>
                            @if($user->certifie)
                                <div class="badge bg-success fs-4 px-3 py-2 mb-2">✔ CERTIFIÉ</div>
                            @endif
                            <div class="row text-start">
                                <div class="col-md-6">
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $user->commune ?? 'Non renseigné' }}</p>
                                    @if($user->telephone)
                                        <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $user->telephone }}</p>
                                    @endif
                                    @if($user->directeur)
                                        <p class="mb-1"><i class="fas fa-user-tie me-2"></i>{{ $user->directeur }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($user->lien_localisation)
                                        <p class="mb-1">
                                            <i class="fas fa-map me-2"></i>
                                            <a href="{{ $user->lien_localisation }}" target="_blank" class="text-white text-decoration-none">
                                                Voir localisation
                                            </a>
                                        </p>
                                    @endif
                                    @auth
                                        @if(auth()->id() === $user->id)
                                            <a href="{{ route('etablissement.edit', $user) }}" class="btn btn-light btn-sm">
                                                <i class="fas fa-edit me-1"></i>Modifier profil
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($user->description)
                        <div class="mt-4 p-3 bg-white bg-opacity-20 rounded">
                            {{ Str::limit($user->description, 150) }}
                        </div>
                    @endif
                </div>

                <!-- ✅ BODY ÉPREUVES -->
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h4 class="mb-0">
                            <i class="fas fa-file-pdf text-primary me-2"></i>
                            Épreuves publiées 
                            <span class="badge bg-primary">{{ $epreuves->total() }}</span>
                        </h4>
                    </div>

                    @if($epreuves->count() > 0)
                        <div class="row g-4 p-4">
                            @foreach($epreuves as $epreuve)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 hover-shadow">
                                        <div class="card-header bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-primary me-2"></i>
                                                <h6 class="mb-0 fw-bold">{{ Str::limit($epreuve->titre, 40) }}</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2">
                                                <span class="badge bg-info">{{ $epreuve->classe }}</span>
                                                <span class="badge bg-warning text-dark ms-1">{{ $epreuve->type_epreuve }}</span>
                                                @if($epreuve->semestre)
                                                    <span class="badge bg-secondary ms-1">{{ $epreuve->semestre }}</span>
                                                @endif
                                            </p>
                                            <p class="small text-muted mb-0">
                                                <i class="fas fa-book me-1"></i>{{ $epreuve->matiere }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('epreuves.download', $epreuve->id) }}" 
                                               class="btn btn-primary w-100">
                                                <i class="fas fa-download me-2"></i>Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-search fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune épreuve publiée</h4>
                            @auth
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('epreuves.create') }}" class="btn btn-primary btn-lg mt-3">
                                        <i class="fas fa-plus me-2"></i>Publier la première !
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- ✅ PAGINATION -->
                @if($epreuves->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            {{ $epreuves->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.hover-shadow:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>
</body>
</html>