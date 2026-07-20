<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>➕ Publier une épreuve - AMEES</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
    --primary: #0d47a1;
    --primary-gradient: linear-gradient(135deg, #0d47a1, #1976d2);
}
body { background: #f4f6f9; }
.navbar { background: var(--primary-gradient) !important; }
.card {
    border-radius: 18px;
    overflow: hidden;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.10);
}
.card-header {
    background: var(--primary-gradient);
    color: white;
    padding: 2rem 1.5rem;
}
.form-control, .form-select {
    border-radius: 12px;
    padding: 12px 16px;
    border: 1px solid #ced4da;
    transition: all 0.25s ease;
}
.form-control:focus, .form-select:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 0.2rem rgba(25,118,210,0.20);
}
.dropzone {
    border: 2px dashed #b0b7c3;
    border-radius: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fafafa;
}
.dropzone:hover {
    border-color: #1976d2;
    background: rgba(25,118,210,0.05);
    transform: translateY(-2px);
}
.dropzone.active-primary { background: rgba(25,118,210,0.10); border-color: #1976d2; }
.dropzone.active-success { background: rgba(40,167,69,0.10); border-color: #28a745; }
.btn-primary {
    background: var(--primary-gradient);
    border: none;
    padding: 14px 0;
    font-size: 1.05rem;
    border-radius: 50px;
}
.btn-primary:hover { transform: translateY(-1px); opacity: 0.95; }
.preview-container {
    max-height: 380px;
    overflow: auto;
    border: 2px dashed #28a745;
    border-radius: 14px;
    background: #f8f9fa;
}
.serie-field, .semestre-field, .type-field {
    transition: all 0.3s ease;
}
.hidden { display: none !important; }
.file-preview-card {
    border-radius: 12px;
    background: white;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">
<div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('epreuves.index') }}">
        <i class="fas fa-arrow-left"></i> Retour aux épreuves
    </a>
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:35px;height:35px;">
            <i class="fas fa-graduation-cap text-dark"></i>
        </div>
        <span class="text-white fw-bold">AMEES</span>
    </div>
</div>
</nav>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-10 col-lg-8">
<div class="card">
    <!-- HEADER -->
    <div class="card-header text-center">
        <h2 class="mb-2 fw-bold">
            <i class="fas fa-file-upload me-3"></i>Publier une nouvelle épreuve
        </h2>
        <p class="mb-0 opacity-75">
            {{ auth()->user()->name }}
            @if(auth()->user()->certifie)
                <span class="badge bg-success ms-2">✔ Certifié</span>
            @endif
        </p>
    </div>

    <!-- BODY -->
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

        <!-- FORM -->
        <form method="POST" action="{{ route('epreuves.store') }}" enctype="multipart/form-data" id="epreuveForm">
            @csrf
            <div class="row g-4">
                <!-- TITRE -->
                <div class="col-12">
                    <label class="form-label fw-bold">📝 Titre de l'épreuve <span class="text-danger">*</span></label>
                    <input type="text" name="titre" class="form-control form-control-lg @error('titre') is-invalid @enderror"
                           value="{{ old('titre') }}" required placeholder="Ex: Contrôle de Mathématiques - 1ère S1">
                    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- CLASSE -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">📚 Classe <span class="text-danger">*</span></label>
                    <select name="classe" id="classe" class="form-select @error('classe') is-invalid @enderror" required>
                        <option value="">Choisir une classe</option>
                        <option value="3ème" {{ old('classe') == '3ème' ? 'selected' : '' }}>3ème</option>
                        <option value="1ère" {{ old('classe') == '1ère' ? 'selected' : '' }}>1ère</option>
                        <option value="Terminale" {{ old('classe') == 'Terminale' ? 'selected' : '' }}>Terminale</option>
                    </select>
                    @error('classe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- SERIE -->
                <div class="col-md-6 serie-field {{ old('classe') == '3ème' ? 'hidden' : '' }}" id="serieField">
                    <label class="form-label fw-bold">🎓 Série <span class="text-danger">*</span></label>
                    <select name="serie" id="serie" class="form-select @error('serie') is-invalid @enderror">
                        <option value="">Choisir une série</option>
                        <option value="A" {{ old('serie') == 'A' ? 'selected' : '' }}>Série A</option>
                        <option value="B" {{ old('serie') == 'B' ? 'selected' : '' }}>Série B</option>
                        <option value="C" {{ old('serie') == 'C' ? 'selected' : '' }}>Série C</option>
                        <option value="D" {{ old('serie') == 'D' ? 'selected' : '' }}>Série D</option>
                    </select>
                    @error('serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- TYPE -->
                <div class="col-md-6 type-field" id="typeEpreuveContainer">
                    <label class="form-label fw-bold">🎯 Type d'épreuve <span class="text-danger">*</span></label>
                    <select name="type_epreuve" id="typeEpreuve" class="form-select @error('type_epreuve') is-invalid @enderror" required>
                        <option value="">Choisir le type</option>
                        <option value="Devoir" {{ old('type_epreuve') == 'Devoir' ? 'selected' : '' }}>📚 Devoir</option>
                        <option value="Examen Blanc" {{ old('type_epreuve') == 'Examen Blanc' ? 'selected' : '' }}>📋 Examen Blanc</option>
                    </select>
                    @error('type_epreuve') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- SEMESTRE -->
                <div class="col-md-6 semestre-field" id="semestreField">
                    <label class="form-label fw-bold">📅 Semestre <span class="text-danger">*</span></label>
                    <select name="semestre" id="semestre" class="form-select @error('semestre') is-invalid @enderror">
                        <option value="">Choisir un semestre</option>
                        <option value="S1" {{ old('semestre') == 'S1' ? 'selected' : '' }}>Semestre 1 (S1)</option>
                        <option value="S2" {{ old('semestre') == 'S2' ? 'selected' : '' }}>Semestre 2 (S2)</option>
                    </select>
                    @error('semestre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- MATIERE -->
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
                    @error('matiere') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- DESCRIPTION -->
                <div class="col-12">
                    <label class="form-label fw-bold">📄 Description (optionnel)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Détails, consignes particulières, durée...">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- FICHIER -->
                <div class="col-12">
                    <label class="form-label fw-bold">📎 Fichier de l'épreuve <span class="text-danger">*</span></label>
                    <div class="dropzone p-5 text-center" id="dropzone">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">Glisser-déposer ou cliquer pour sélectionner</h6>
                        <small class="text-muted">PDF, Word, Images (Max 10 Mo)</small>
                        <input type="file" name="fichier" id="fichier" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    <div id="preview" class="preview-container d-none p-3 mt-3"></div>
                    @error('fichier') <div class="alert alert-danger mt-2">{{ $message }}</div> @enderror
                </div>

                <!-- CORRIGÉ -->
                <div class="col-12">
                    <label class="form-label fw-bold">📋 Fichier corrigé (optionnel)</label>
                    <div class="dropzone p-5 text-center" id="dropzoneReponses">
                        <i class="fas fa-file-check fa-3x text-success mb-3"></i>
                        <h6 class="fw-bold">Ajouter le corrigé (recommandé)</h6>
                        <small class="text-muted">PDF, Word, Images</small>
                        <input type="file" name="reponses" id="reponses" class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                    <div id="previewReponses" class="preview-container d-none p-3 mt-3"></div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-grid gap-3 mt-5">
                <button type="submit" class="btn btn-primary btn-lg fw-bold" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i>Publier l'épreuve maintenant
                </button>
                <a href="{{ route('epreuves.index') }}" class="btn btn-outline-secondary btn-lg">
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
document.addEventListener('DOMContentLoaded', function () {
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

    function updateFields() {
        const classe = classeSelect.value;
        const type = typeEpreuve.value;

        // === SÉRIE ===
        if (classe === '3ème') {
            serieField.classList.add('hidden');
            serieInput.removeAttribute('required');
            serieInput.value = '';
        } else {
            serieField.classList.remove('hidden');
            serieInput.setAttribute('required', 'required');
        }

        // === TYPE pour 1ère ===
        if (classe === '1ère') {
            typeEpreuve.value = 'Devoir';
            typeEpreuve.querySelector('option[value="Examen Blanc"]').disabled = true;
        } else {
            typeEpreuve.querySelector('option[value="Examen Blanc"]').disabled = false;
        }

        // === SEMESTRE (Correction importante) ===
        // On cache le semestre UNIQUEMENT pour Examen Blanc
        // Pour 1ère (qui est toujours Devoir), on affiche le semestre
        if (type === 'Examen Blanc') {
            semestreField.classList.add('hidden');
            semestreInput.removeAttribute('required');
            semestreInput.value = '';
        } else {
            semestreField.classList.remove('hidden');
            semestreInput.setAttribute('required', 'required');
        }
    }

    classeSelect.addEventListener('change', updateFields);
    typeEpreuve.addEventListener('change', updateFields);

    // Initialisation
    updateFields();

    // ====================== DRAG & DROP & PREVIEW (inchangé) ======================
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults);
        dropzoneReponses.addEventListener(eventName, preventDefaults);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('active-primary'));
        dropzoneReponses.addEventListener(eventName, () => dropzoneReponses.classList.add('active-success'));
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('active-primary'));
        dropzoneReponses.addEventListener(eventName, () => dropzoneReponses.classList.remove('active-success'));
    });

    dropzone.addEventListener('drop', function (e) {
        const files = e.dataTransfer.files;
        if (files.length) {
            fichierInput.files = files;
            previewFile(files[0], 'preview', 'fichier');
        }
    });

    fichierInput.addEventListener('change', function (e) {
        if (e.target.files.length) previewFile(e.target.files[0], 'preview', 'fichier');
    });

    dropzoneReponses.addEventListener('drop', function (e) {
        const files = e.dataTransfer.files;
        if (files.length) {
            reponsesInput.files = files;
            previewFile(files[0], 'previewReponses', 'reponses');
        }
    });

    reponsesInput.addEventListener('change', function (e) {
        if (e.target.files.length) previewFile(e.target.files[0], 'previewReponses', 'reponses');
    });

    function previewFile(file, previewId, inputId) {
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('❌ Fichier trop volumineux (max 10 Mo)');
            return;
        }
        const previewBox = document.getElementById(previewId);
        let icon = 'fa-file';
        if (file.type.includes('pdf')) icon = 'fa-file-pdf text-danger';
        else if (file.type.includes('word') || file.name.endsWith('.docx')) icon = 'fa-file-word text-primary';
        else if (file.type.includes('image')) icon = 'fa-file-image text-success';

        previewBox.innerHTML = `
            <div class="file-preview-card d-flex align-items-center p-3 border">
                <i class="fas ${icon} fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <strong>${file.name}</strong><br>
                    <small class="text-muted">${formatFileSize(file.size)}</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearPreview('${previewId}', '${inputId}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        previewBox.classList.remove('d-none');
    }

    dropzone.addEventListener('click', () => fichierInput.click());
    dropzoneReponses.addEventListener('click', () => reponsesInput.click());

    form.addEventListener('submit', function (e) {
        if (!fichierInput.files.length) {
            e.preventDefault();
            alert('❌ Veuillez sélectionner un fichier d\'épreuve');
            return false;
        }
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Publication en cours...`;
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