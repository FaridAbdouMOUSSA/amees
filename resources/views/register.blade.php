<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - AMEES</title>

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
        <div class="col-md-6">

            <!-- CARD FORM -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h2 class="text-center text-primary fw-bold mb-4">
                        Créer un compte
                    </h2>

                    <!-- SUCCESS MESSAGE -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- NOM -->
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="form-control"
                                   placeholder="Votre nom"
                                   required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   placeholder="exemple@mail.com"
                                   required>
                        </div>

                        <!-- ROLE (IMPORTANT AMEES) -->
                        <div class="mb-3">
                            <label class="form-label">Type de compte</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Choisir un rôle --</option>
                                <option value="eleve">Élève</option>
                                <option value="etablissement">Établissement</option>
                            </select>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password"
                                   class="form-control"
                                   placeholder="********"
                                   required>
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mb-4">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="********"
                                   required>
                        </div>

                        <!-- SUBMIT -->
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            S'inscrire
                        </button>

                    </form>

                </div>
            </div>

            <!-- LOGIN LINK -->
            <p class="text-center mt-3 text-muted">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-primary">Connexion</a>
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