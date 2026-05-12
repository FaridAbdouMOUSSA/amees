<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMEES - Plateforme éducative</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">AMEES</a>

    <div class="ms-auto">
        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Connexion</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
    </div>
</nav>

<!-- HERO -->
<div class="container text-center py-5">
    <h1 class="display-4 fw-bold text-primary">Bienvenue sur AMEES</h1>

    <p class="lead mt-3 text-muted">
        La plateforme où les élèves accèdent aux épreuves et les établissements partagent leurs contenus.
    </p>

    <a href="{{ route('register') }}" class="btn btn-success btn-lg mt-4 px-5">
        Commencer maintenant
    </a>
</div>

<!-- FEATURES -->
<div class="container py-5">
    <div class="row text-center g-4">

        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <h3 class="text-primary">📚 Épreuves</h3>
                <p>Accède à des milliers de sujets d’examens.</p>
                <a href="/epreuves" class="btn btn-outline-primary btn-sm">Explorer</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <h3 class="text-success">💬 Interaction</h3>
                <p>Like, commente et échange avec la communauté.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-white shadow-sm rounded">
                <h3 class="text-warning">🏆 Classement</h3>
                <p>Découvre les meilleurs établissements.</p>
                <a href="{{ route('classement') }}" class="btn btn-outline-warning btn-sm">Voir</a>
            </div>
        </div>

    </div>
</div>

<!-- CTA -->
<div class="container text-center py-5 bg-white rounded shadow-sm">
    <h2 class="text-primary">Rejoins AMEES maintenant</h2>
    <p class="text-muted">Plus de 1000 épreuves disponibles</p>

    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
        Créer un compte gratuit
    </a>
</div>

<!-- FOOTER -->
<footer class="text-center py-4 mt-5 text-muted">
    © {{ date('Y') }} AMEES - Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>