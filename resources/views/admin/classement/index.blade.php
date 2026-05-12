@extends('admin.dashboard')

@section('title', '🏆 Classement Admin - AMEES')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold text-primary">
                <i class="fas fa-trophy me-3"></i>
                Classement Complet des Établissements
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour Dashboard
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 card-hover">
            <div class="card-header bg-gradient bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-crown me-2"></i>
                    Top {{ $classement->count() }} • {{ $classement->total() }} total
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>🏆 Position</th>
                                <th>🏫 Établissement</th>
                                <th>📚 Épreuves</th>
                                <th>⭐ Score</th>
                                <th>📅 Inscrit</th>
                                <th>👤 Statut</th>
                                <th>⚙️ Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classement as $index => $etablissement)
                            <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                <td>
                                    <strong class="text-warning fs-5">
                                        {{ $classement->firstItem() + $index }}
                                        @if($index < 3)
                                            <i class="fas fa-crown ms-1"></i>
                                        @endif
                                    </strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-school text-primary me-3 fs-4"></i>
                                        <div>
                                            <strong>{{ $etablissement->name }}</strong>
                                            <br><small class="text-muted">{{ $etablissement->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info fs-6 px-3 py-2">{{ $etablissement->epreuves_count }}</span>
                                </td>
                                <td>
                                    <strong class="text-success fs-5">{{ $etablissement->score }} <small>pts</small></strong>
                                </td>
                                <td>{{ $etablissement->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($etablissement->certifie)
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Certifié
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($etablissement->certifie)
                                            <form method="POST" action="{{ route('admin.decertifier', $etablissement->id) }}" class="d-inline" 
                                                onsubmit="return confirm('Décertifier {{ $etablissement->name }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Décertifier">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.valider', $etablissement->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Certifier">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.etablissements', $etablissement) }}" class="btn btn-outline-primary" title="Profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Affichage {{ $classement->firstItem() }} à {{ $classement->lastItem() }} sur {{ $classement->total() }}
                        </div>
                        <div>
                            {{ $classement->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection