<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d47a1;
        }
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.25);
        }
        .btn-login {
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background: #0d47a1;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="fas fa-graduation-cap"></i> AMEES
        </a>
        <div class="ms-auto">
            <a href="{{ route('register') }}" class="btn btn-light">Créer un compte</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="login-card card">
                <!-- Header -->
                <div class="card-header">
                    <h2 class="fw-bold mb-1">Connexion</h2>
                    <p class="mb-0 opacity-90">Accédez à votre espace AMEES</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <!-- Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control"
                                   placeholder="exemple@ecole.edu.bj"
                                   value="{{ old('email') }}"
                                   required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mot de passe</label>
                            <div class="input-group">
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control"
                                       placeholder="••••••••"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Se souvenir de moi</label>
    </div>
    
 <a href="{{ route('password.request') }}" class="text-primary small">Mot de passe oublié ?</a>
</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-login fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                        </button>
                    </form>

                </div>

                <div class="card-footer bg-light text-center py-4">
                    <p class="mb-0 text-muted">
                        Pas encore de compte ? 
                        <a href="{{ route('register') }}" class="text-primary fw-semibold">Créer un compte</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<footer class="text-center py-4 text-muted small">
    © {{ date('Y') }} AMEES - Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>