<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profil - {{ $user->name }} | AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d47a1;
            --primary-gradient: linear-gradient(135deg, #0d47a1, #1976d2);
        }
        .navbar { background: var(--primary-gradient) !important; }
        .card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.2rem 1.5rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }
    </style>
</head>
<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('etablissement.profil', $user) }}">
                <i class="fas fa-arrow-left"></i> Retour au profil
            </a>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                    <i class="fas fa-graduation-cap text-dark"></i>
                </div>
                <span class="fw-bold text-white">AMEES</span>
            </div>
            <a class="btn btn-outline-light rounded-pill" href="{{ route('epreuves.index') }}">
                📚 Épreuves
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3 class="mb-1 fw-bold"><i class="fas fa-edit me-3"></i>Modifier le profil</h3>
                        <p class="mb-0 opacity-90">{{ $user->name }}</p>
                    </div>

                    <form method="POST" action="{{ route('etablissement.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            <!-- Nom -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Nom de l'établissement <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <!-- Dernier changement de nom -->
                                @if($user->derniere_modification_nom)
                                    @php
                                        $dateModif = \Carbon\Carbon::parse($user->derniere_modification_nom);
                                        $prochainChangement = $dateModif->copy()->addYear();
                                    @endphp
                                    <div class="alert alert-warning mt-3 small">
                                        <i class="fas fa-clock me-2"></i>
                                        Dernier changement : <strong>{{ $dateModif->format('d/m/Y') }}</strong><br>
                                        Prochain changement possible : <strong>{{ $prochainChangement->format('d/m/Y') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <!-- Photo -->
                            <div class="mb-5">
                                <label class="form-label fw-bold">Photo de profil</label>
                                <div class="d-flex align-items-center gap-4">
                                    @if($user->photo_profil)
                                        <img src="{{ Storage::url($user->photo_profil) }}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;" alt="Photo actuelle">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 120px; height: 120px; font-size: 2.8rem;">
                                            <i class="fas fa-school"></i>
                                        </div>
                                    @endif>
                                    <div class="flex-grow-1">
                                        <input type="file" name="photo_profil" class="form-control @error('photo_profil') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted">JPG, PNG, GIF - Max 2 Mo</small>
                                        @error('photo_profil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de contact -->
                            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-address-book"></i> Informations de contact</h5>
                            <div class="row g-4 mb-5">

                                <!-- Téléphone avec code pays -->
 <!-- Téléphones (2 numéros) -->
<div class="col-md-12">
    <label class="form-label fw-bold">Numéros de téléphone</label>
    
    <div class="row g-3">
        <!-- Premier numéro -->
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">+229</span>
<input type="tel"
       name="telephone1"
       id="telephone1"
       class="form-control"
       value="{{ old('telephone1', isset($user->telephone[0]) ? preg_replace('/[^0-9]/', '', str_replace('+229', '', $user->telephone[0])) : '') }}"
       placeholder="01 23 45 67 89"
       maxlength="10">
            </div>
            <small class="text-muted">Principal</small>
        </div>

        <!-- Deuxième numéro -->
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">+229</span>
<input type="tel"
       name="telephone2"
       id="telephone2"
       class="form-control"
       value="{{ old('telephone2', isset($user->telephone[1]) ? preg_replace('/[^0-9]/', '', str_replace('+229', '', $user->telephone[1])) : '') }}"
       placeholder="01 23 45 67 89"
       maxlength="10">
            </div>
            <small class="text-muted">Secondaire</small>
        </div>
    </div>
    @error('telephone')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

                                <!-- Directeur, Commune, Lien... (inchangés) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Directeur / Directrice</label>
                                    <input type="text" name="directeur" class="form-control" value="{{ old('directeur', $user->directeur) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Commune</label>
                                    <select name="commune" class="form-select">
                                        <option value="">Choisir une commune</option>
                                        @foreach($communes as $commune)
                                            <option value="{{ $commune }}" {{ old('commune', $user->commune) == $commune ? 'selected' : '' }}>{{ $commune }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Lien Google Maps</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="url" name="lien_localisation" class="form-control" value="{{ old('lien_localisation', $user->lien_localisation) }}" placeholder="https://maps.google.com/...">
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Description de l'établissement</label>
                                <textarea name="description" id="description" class="form-control" rows="5" maxlength="1000" placeholder="Présentez votre établissement...">{{ old('description', $user->description) }}</textarea>
                                <div class="d-flex justify-content-between mt-1 small">
                                    <span class="text-muted">Maximum 1000 caractères</span>
                                    <span id="charCount" class="text-muted">0 / 1000</span>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer bg-white border-0 p-4">
                            <div class="d-flex gap-3 justify-content-between">
                                <a href="{{ route('etablissement.profil', $user) }}" class="btn btn-outline-secondary px-4">← Retour au profil</a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold">
                                    <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatage automatique des numéros
    const telInputs = ['telephone1', 'telephone2'];
    
    telInputs.forEach(id => {
        const input = document.getElementById(id);
input.addEventListener('input', function () {

    // chiffres uniquement
    let value = this.value.replace(/\D/g, '');

    // maximum 10 chiffres
    value = value.substring(0, 10);

    // format 01 23 45 67 89
    value = value.replace(
        /(\d{0,2})(\d{0,2})(\d{0,2})(\d{0,2})(\d{0,2})/,
        function (_, a, b, c, d, e) {
            return [a, b, c, d, e].filter(Boolean).join(' ');
        }
    );

    this.value = value;
});
        }
    });

    // Compteur de caractères
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    if (textarea && charCount) {
        function updateCount() {
            let count = textarea.value.length;
            charCount.textContent = `${count} / 1000`;
            if (count > 950) charCount.classList.add('text-danger');
            else charCount.classList.remove('text-danger');
        }
        updateCount();
        textarea.addEventListener('input', updateCount);
    }
});
</script>
</body>
</html>