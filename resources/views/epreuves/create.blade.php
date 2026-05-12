<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>➕ Publier une épreuve - AMEES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .preview-container {
            max-height: 400px;
            overflow: auto;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
        }
        .semestre-field, .serie-field {
            transition: all 0.3s ease;
        }
        .semestre-field.hidden, .serie-field.hidden {
            display: none;
        }
        .type-epreuve .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('epreuves.index') }}">
            <i class="fas fa-arrow-left me-2"></i>Retour épreuves
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h2 class="mb-2">
                        <i class="fas fa-file-upload me-3"></i>Publier une épreuve
                    </h2>
                    <div class="badge bg-success fs-6">
                        {{ auth()->user()->name }} 
                        @if(auth()->user()->certifie) ✔ Certifié @endif
                    </div>
                </div>
                
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-1"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('epreuves.store') }}" enctype="multipart/form-data" id="epreuveForm">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold fs-5">📝 Titre de l'épreuve <span class="text-danger">*</span></label>
                                <input type="text" name="titre" class="form-control form-control-lg @error('titre') is-invalid @enderror" 
                                       value="{{ old('titre') }}" required placeholder="Ex: Contrôle Maths 3ème S1">
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">📚 Classe <span class="text-danger">*</span></label>
                                <select name="classe" id="classe" class="form-select @error('classe') is-invalid @enderror" required>
                                    <option value="">Choisir une classe</option>
                                    <option value="3ème" {{ old('classe') == '3ème' ? 'selected' : '' }}>3ème</option>
                                    <option value="1ère" {{ old('classe') == '1ère' ? 'selected' : '' }}>1ère</option>
                                    <option value="Terminale" {{ old('classe') == 'Terminale' ? 'selected' : '' }}>Terminale</option>
                                </select>
                                @error('classe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 serie-field @if(old('classe') == '3ème') hidden @endif" id="serieField">
                                <label class="form-label fw-bold">🎓 Série <span class="text-danger">*</span></label>
                                <select name="serie" id="serie" class="form-select @error('serie') is-invalid @enderror">
                                    <option value="">Choisir une série</option>
                                    <option value="A" {{ old('serie') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('serie') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('serie') == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('serie') == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                                @error('serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 type-epreuve">
                                <label class="form-label fw-bold">🎯 Type d'épreuve <span class="text-danger">*</span></label>
                                <select name="type_epreuve" id="typeEpreuve" class="form-select @error('type_epreuve') is-invalid @enderror fw-bold" required>
                                    <option value="">Choisir le type</option>
                                    <option value="Devoir" {{ old('type_epreuve') == 'Devoir' ? 'selected' : '' }}>
                                        📚 Devoir
                                    </option>
                                    <option value="Examen Blanc" {{ old('type_epreuve') == 'Examen Blanc' ? 'selected' : '' }}>
                                        📋 Examen Blanc
                                    </option>
                                </select>
                                @error('type_epreuve')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 semestre-field @if(old('type_epreuve') == 'Examen Blanc') hidden @endif" id="semestreField">
                                <label class="form-label fw-bold">📅 Semestre <span class="text-danger">*</span></label>
                                <select name="semestre" id="semestre" class="form-select @error('semestre') is-invalid @enderror" required>
                                    <option value="">Choisir un semestre</option>
                                    <option value="S1" {{ old('semestre') == 'S1' ? 'selected' : '' }}>
                                        <i class="fas fa-calendar-alt me-1"></i>Semestre 1
                                    </option>
                                    <option value="S2" {{ old('semestre') == 'S2' ? 'selected' : '' }}>
                                        <i class="fas fa-calendar-alt me-1"></i>Semestre 2
                                    </option>
                                </select>
                                @error('semestre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">📖 Matière <span class="text-danger">*</span></label>
                                <select name="matiere" id="matiere" class="form-select @error('matiere') is-invalid @enderror" required>
                                    <option value="">Choisir une matière</option>
                                    <option value="Mathématiques" {{ old('matiere') == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                    <option value="Français" {{ old('matiere') == 'Français' ? 'selected' : '' }}>Français</option>
                                    <option value="Histoire-Géo" {{ old('matiere') == 'Histoire-Géo' ? 'selected' : '' }}>Histoire-Géo</option>
                                    <option value="SVT" {{ old('matiere') == 'SVT' ? 'selected' : '' }}>SVT</option>
                                    <option value="Physique-Chimie" {{ old('matiere') == 'Physique-Chimie' ? 'selected' : '' }}>Physique-Chimie</option>
                                    <option value="Anglais" {{ old('matiere') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                    <option value="Espagnol" {{ old('matiere') == 'Espagnol' ? 'selected' : '' }}>Espagnol</option>
                                    <option value="Philosophie" {{ old('matiere') == 'Philosophie' ? 'selected' : '' }}>Philosophie</option>
                                </select>
                                @error('matiere')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">📄 Description (optionnel)</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Objectifs de l'épreuve, consignes spéciales...">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold fs-5">📎 Fichier de l'épreuve <span class="text-danger">*</span></label>
                                <div class="dropzone p-4 text-center border border-dashed border-primary rounded-3 mb-3" id="dropzone">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <p class="mb-2"><strong>Glisser-déposer</strong> ou <strong>cliquer</strong> pour sélectionner</p>
                                    <p class="text-muted small mb-0">PDF, Word, Image (Max 10 Mo)</p>
                                    <input type="file" name="fichier" id="fichier" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                </div>
                                <div id="preview" class="preview-container d-none p-3 bg-light rounded"></div>
                                @error('fichier')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">📋 Fichier corrigé (optionnel)</label>
                                <div class="dropzone p-4 text-center border border-dashed border-secondary rounded-3 mb-3" id="dropzoneReponses">
                                    <i class="fas fa-file-check fa-3x text-success mb-3"></i>
                                    <p class="mb-2">Ajouter le corrigé</p>
                                    <input type="file" name="reponses" id="reponses" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                                <div id="previewReponses" class="preview-container d-none p-3 bg-light rounded"></div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>🚀 Publier l'épreuve
                            </button>
                            <a href="{{ route('epreuves.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classeSelect = document.getElementById('classe');
    const typeEpreuve = document.getElementById('typeEpreuve');
    const semestreField = document.getElementById('semestreField');
    const semestreInput = document.getElementById('semestre');
    const serieField = document.getElementById('serieField');
    const serieInput = document.getElementById('serie');
    
    const dropzone = document.getElementById('dropzone');
    const fichierInput = document.getElementById('fichier');
    const preview = document.getElementById('preview');
    const dropzoneReponses = document.getElementById('dropzoneReponses');
    const reponsesInput = document.getElementById('reponses');
    const previewReponses = document.getElementById('previewReponses');
    const form = document.getElementById('epreuveForm');
    const submitBtn = document.getElementById('submitBtn');

    classeSelect.addEventListener('change', function() {
        if (this.value === '3ème') {
            serieField.classList.add('hidden');
            serieInput.removeAttribute('required');
            serieInput.value = '';
        } else {
            serieField.classList.remove('hidden');
            serieInput.setAttribute('required', 'required');
        }
    });

    typeEpreuve.addEventListener('change', function() {
        if (this.value === 'Examen Blanc') {
            semestreField.classList.add('hidden');
            semestreInput.removeAttribute('required');
            semestreInput.value = '';
        } else {
            semestreField.classList.remove('hidden');
            semestreInput.setAttribute('required', 'required');
        }
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        dropzoneReponses.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('bg-primary', 'text-white'), false);
        dropzoneReponses.addEventListener(eventName, () => dropzoneReponses.classList.add('bg-success', 'text-white'), false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('bg-primary', 'text-white'), false);
        dropzoneReponses.addEventListener(eventName, () => dropzoneReponses.classList.remove('bg-success', 'text-white'), false);
    });

    dropzone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length) previewFile(files[0], 'preview', 'fichier');
    });
    
    fichierInput.addEventListener('change', function(e) {
        if (e.target.files.length) previewFile(e.target.files[0], 'preview', 'fichier');
    });

    dropzoneReponses.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length) previewFile(files[0], 'previewReponses', 'reponses');
    });
    
    reponsesInput.addEventListener('change', function(e) {
        if (e.target.files.length) previewFile(e.target.files[0], 'previewReponses', 'reponses');
    });

    function previewFile(file, previewId, inputId) {
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('❌ Fichier trop volumineux (max 10 Mo)');
            return;
        }

        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function() {
            const preview = document.getElementById(previewId);
            preview.innerHTML = `
                <div class="d-flex align-items-center p-2 border rounded">
                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                    <div class="flex-grow-1">
                        <strong>${file.name}</strong><br>
                        <small class="text-muted">${formatFileSize(file.size)}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearPreview('${previewId}', '${inputId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            preview.classList.remove('d-none');
        };
    }

    dropzone.addEventListener('click', () => fichierInput.click());
    dropzoneReponses.addEventListener('click', () => reponsesInput.click());

    form.addEventListener('submit', function(e) {
        if (!fichierInput.files.length) {
            e.preventDefault();
            alert('❌ Veuillez sélectionner un fichier d\'épreuve');
            dropzone.scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        if (classeSelect.value !== '3ème' && !serieInput.value) {
            e.preventDefault();
            alert('❌ Veuillez sélectionner une série pour 1ère/Terminale');
            serieField.scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        if (typeEpreuve.value === 'Devoir' && !semestreInput.value) {
            e.preventDefault();
            alert('❌ Veuillez sélectionner un semestre pour un Devoir');
            semestreField.scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Publication en cours...';
        submitBtn.disabled = true;
    });

    window.clearPreview = function(previewId, inputId) {
        document.getElementById(previewId).classList.add('d-none');
        document.getElementById(inputId).value = '';
    };

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

</body>
</html>