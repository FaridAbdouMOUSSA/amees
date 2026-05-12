<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AMEES</title>

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

<!-- CONTAINER -->
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h2 class="text-center text-primary fw-bold mb-4">
                        Connexion
                    </h2>

                    <!-- 🔥 MESSAGE SUCCESS -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- FORM -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="exemple@mail.com"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="********"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            Se connecter
                        </button>
                    </form>

                </div>
            </div>

            <!-- LINK REGISTER -->
            <p class="text-center mt-3 text-muted">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-primary">Créer un compte</a>
            </p>

        </div>
    </div>

</div>

<!-- FOOTER -->
<footer class="text-center py-4 mt-5 text-muted">
    © {{ date('Y') }} AMEES - Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>