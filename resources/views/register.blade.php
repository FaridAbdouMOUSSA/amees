<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - AMEES</title>
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
        .register-card {
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
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.25);
        }
        .btn-register {
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 10px;
        }
        .progress {
            height: 8px;
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
            <a href="{{ route('login') }}" class="btn btn-light">Se connecter</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="register-card card">
                <!-- Header -->
                <div class="card-header">
                    <h2 class="fw-bold mb-1">Créer un compte</h2>
                    <p class="mb-0 opacity-90">Rejoignez la communauté AMEES</p>
                </div>

                <div class="card-body p-4 p-md-5">

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom complet</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="form-control"
                                   placeholder="Nom de l'élève ou de l'établissement"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   placeholder="exemple@ecole.edu.bj"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Type de compte</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Choisissez votre rôle --</option>
                                <option value="eleve" {{ old('role') == 'eleve' ? 'selected' : '' }}>
                                    Élève / Étudiant
                                </option>
                                <option value="etablissement" {{ old('role') == 'etablissement' ? 'selected' : '' }}>
                                    Établissement scolaire
                                </option>
                            </select>
                            <small class="text-muted">Ce choix est important.</small>
                        </div>

                        <!-- Mot de passe avec vérification de solidité -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mot de passe</label>
                            <div class="input-group">
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control"
                                       placeholder="Créez un mot de passe solide"
                                       required onkeyup="checkPasswordStrength()">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <!-- Indicateur de force -->
                            <div class="mt-2">
                                <div id="passwordStrength" class="progress" style="height: 8px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="strengthText" class="text-muted"></small>
                            </div>
                            <small class="text-muted">
    Le mot de passe doit contenir au moins :<br>
    • 8 caractères<br>
    • Une majuscule et une minuscule<br>
    • Un chiffre<br>
    • Un caractère spécial (ex: ! @ # $ %)
</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                            <input type="password" 
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirmez votre mot de passe"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-register fw-bold">
                            <i class="fas fa-user-plus me-2"></i> Créer mon compte
                        </button>
                    </form>

                </div>

                <div class="card-footer bg-light text-center py-4">
                    <p class="mb-0 text-muted">
                        Déjà un compte ? 
                        <a href="{{ route('login') }}" class="text-primary fw-semibold">Se connecter</a>
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
// Vérification de la solidité du mot de passe
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthBar.className = 'progress-bar';
        strengthText.textContent = '';
        return;
    }

    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    let text = '';
    let color = '';

    switch(strength) {
        case 0:
        case 1:
            text = "Très faible";
            color = "bg-danger";
            break;
        case 2:
            text = "Faible";
            color = "bg-warning";
            break;
        case 3:
            text = "Moyen";
            color = "bg-info";
            break;
        case 4:
            text = "Fort";
            color = "bg-success";
            break;
        case 5:
            text = "Très fort";
            color = "bg-success";
            break;
    }

    strengthBar.style.width = (strength * 20) + '%';
    strengthBar.className = `progress-bar ${color}`;
    strengthText.textContent = text;
    strengthText.className = color === 'bg-success' ? 'text-success' : 'text-muted';
}

function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    if (pwd.type === "password") {
        pwd.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pwd.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>