@extends('admin.layout')

@section('title', '🏫 Établissements - AMEES Admin')

@section('content')
<!-- CONTENU PRINCIPAL -->
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 fw-bold mb-1">
                <i class="fas fa-school me-2 text-primary"></i>
                Gestion des Établissements
            </h2>
            <p class="text-muted mb-0">{{ $etablissements->total() }} établissements enregistrés</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold">🔍 Recherche</label>
                    <input type="text" name="q" class="form-control" 
                           value="{{ request('q') }}" 
                           placeholder="Nom de l'établissement ou email...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">📊 Statut</label>
                    <select name="certifie" class="form-select">
                        <option value="">Tous les établissements</option>
                        <option value="1" {{ request('certifie') == '1' ? 'selected' : '' }}>✅ Certifiés</option>
                        <option value="0" {{ request('certifie') == '0' ? 'selected' : '' }}>⏳ En attente</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="35%">Établissement</th>
                            <th>Épreuves</th>
                            <th>Téléchargements</th>
                            <th>Inscription</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etablissements as $etablissement)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-circle p-3">
                                        <i class="fas fa-school text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $etablissement->name }}</strong>
                                        @if($etablissement->commune)
                                            <br><small class="text-muted">{{ $etablissement->commune }}</small>
                                        @endif
                                        <br><small class="text-muted">{{ $etablissement->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info fs-6">{{ $etablissement->epreuves_count ?? 0 }}</span></td>
                            <td><span class="badge bg-warning text-dark fs-6">{{ number_format($etablissement->downloads_total ?? 0) }}</span></td>
                            <td><small>{{ $etablissement->created_at->format('d/m/Y') }}</small></td>
                            <td>
                                @if($etablissement->certifie)
                                    <span class="badge bg-success px-3 py-2">✅ Certifié</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">⏳ En attente</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('etablissement.profil', $etablissement) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @if($etablissement->certifie)
                                <form method="POST" action="{{ route('admin.decertifier', $etablissement->id) }}" class="d-inline" onsubmit="return confirm('Décertifier cet établissement ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.valider', $etablissement->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                                </form>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-school fa-4x mb-3 opacity-50"></i>
                                <p>Aucun établissement trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $etablissements->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection