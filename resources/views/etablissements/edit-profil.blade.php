<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profil - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('etablissement.profil', $user) }}">
                <i class="fas fa-arrow-left me-2"></i>Profil
            </a>
            <a class="navbar-brand fw-bold ms-auto" href="{{ route('epreuves.index') }}">Épreuves</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3><i class="fas fa-edit me-2"></i>Modifier profil</h3>
                    </div>
                    <form method="POST" action="{{ route('etablissement.update', $user) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="card-body">
                            <!-- NOM -->
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-5">🏫 Nom de l'établissement <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                <!-- ✅ CORRECTION DATE ICI -->
                                @if($user->derniere_modification_nom)
                                    @php
                                        $dateModif = \Carbon\Carbon::parse($user->derniere_modification_nom);
                                        $prochainChangement = $dateModif->copy()->addYear();
                                    @endphp
                                    <div class="alert alert-warning mt-2">
                                        ⏰ Dernier changement : {{ $dateModif->format('d/m/Y') }}
                                        <br>ℹ️ Prochain changement : {{ $prochainChangement->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>

                            <!-- PHOTO -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">📸 Photo de profil</label>
                                    <input type="file" name="photo_profil" class="form-control" accept="image/*">
                                    @if($user->photo_profil)
                                        <img src="{{ Storage::url($user->photo_profil) }}" 
                                             class="img-thumbnail mt-2 rounded-circle" style="width: 80px; height: 80px;">
                                    @endif
                                </div>
                            </div>

                            <!-- Infos rapides -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">📞 Téléphone</label>
                                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">👨‍💼 Directeur</label>
                                    <input type="text" name="directeur" class="form-control" value="{{ old('directeur', $user->directeur) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">🏘️ Commune</label>
                                    <select name="commune" class="form-select">
                                        <option value="">Choisir...</option>
                                        @foreach($communes as $commune)
                                            <option value="{{ $commune }}" {{ old('commune', $user->commune) == $commune ? 'selected' : '' }}>
                                                {{ $commune }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">🗺️ Lien localisation</label>
                                    <input type="url" name="lien_localisation" class="form-control" 
                                           value="{{ old('lien_localisation', $user->lien_localisation) }}">
                                </div>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-5">📝 Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $user->description) }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex gap-3">
                                <a href="{{ route('etablissement.profil', $user) }}" class="btn btn-outline-secondary">
                                    ← Retour profil
                                </a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <i class="fas fa-save me-2"></i>Sauvegarder
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>