@extends('admin.layout')

@section('title', '🏫 Établissements - AMEES')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0">
        <i class="fas fa-school me-2 text-success"></i>
        Établissements ({{ $etablissements->total() }})
    </h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Dashboard
    </a>
</div>

<div class="card shadow">
    <div class="card-header bg-light border-0">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Recherche</label>
                <input type="text" name="q" class="form-control" 
                       value="{{ request('q') }}" placeholder="Nom ou email...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="certifie" class="form-select">
                    <option value="">Tous</option>
                    <option value="1" {{ request('certifie') == '1' ? 'selected' : '' }}>Certifiés</option>
                    <option value="0" {{ request('certifie') == '0' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4 w-100">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Établissement</th>
                        <th>Épreuves</th>
                        <th>Downloads</th>
                        <th>Inscrit</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etablissements as $etablissement)
                    <tr>
                        <td>
                            <strong>{{ $etablissement->name }}</strong>
                            @if($etablissement->commune)
                            <br><small class="text-muted">{{ $etablissement->commune }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $etablissement->epreuves_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $etablissement->downloads_total }}</span>
                        </td>
                        <td>
                            {{ $etablissement->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($etablissement->certifie)
                                <span class="badge bg-success px-3 py-2">✅ Certifié</span>
                            @else
                                <span class="badge bg-warning px-3 py-2">⏳ En attente</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('etablissement.profil', $etablissement) }}" 
                                class="btn btn-outline-primary" title="Voir profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($etablissement->certifie)
                                    {{-- DECERTIFIER --}}
                                    <form method="POST" action="{{ route('admin.decertifier', $etablissement->id) }}" class="d-inline" 
                                        style="margin-left: -1px;" onsubmit="return confirm('Décertifier {{ $etablissement->name }} ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger border-left-0" title="Décertifier">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    {{-- CERTIFIER --}}
                                    <form method="POST" action="{{ route('admin.valider', $etablissement->id) }}" class="d-inline" 
                                        style="margin-left: -1px;">
                                        @csrf
                                        <button type="submit" class="btn btn-success border-left-0" title="Certifier">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-school fa-3x mb-3"></i>
                            <p>Aucun établissement trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $etablissements->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection