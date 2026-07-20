@forelse($classement as $index => $etab)
    <div class="ranking-row p-4 border-bottom d-flex align-items-center">
        <div class="me-3 fw-bold text-muted" style="width:40px;">#{{ $index + 1 }}</div>
        <div class="flex-grow-1">
            <h6 class="mb-1">{{ $etab->name }}</h6>
            @if($etab->certifie)
                <span class="badge bg-success">✓ Certifié</span>
            @endif
        </div>
        <div class="text-end">
            <span class="badge bg-primary me-2">{{ $etab->epreuves_count }} épreuves</span>
            <span class="badge bg-info me-2">{{ $etab->total_downloads ?? 0 }} dl</span>
            <span class="badge bg-danger">{{ $etab->total_likes ?? 0 }} likes</span>
        </div>
    </div>
@empty
    <p class="text-center py-5 text-muted">Aucun établissement trouvé.</p>
@endforelse

{{-- Pagination (optionnelle mais recommandée) --}}
@if($classement->hasPages())
    <div class="p-3 border-top bg-light">
        {{ $classement->appends(request()->query())->links() }}
    </div>
@endif