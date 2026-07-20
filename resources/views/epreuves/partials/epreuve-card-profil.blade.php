<div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm border-0 card-epreuve">
        <!-- Card Header -->
        <div class="card-header bg-white border-bottom px-3 py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:32px;height:32px;min-width:32px;">
                        <i class="fas fa-school text-white" style="font-size:13px;"></i>
                    </div>
                    @if($epreuve->user)
                        <a href="{{ route('etablissement.profil', $epreuve->user) }}"
                           class="text-decoration-none fw-bold text-primary small">
                            {{ Str::limit($epreuve->user->name, 22) }}
                        </a>
                    @else
                        <span class="fw-bold text-muted small">Anonyme</span>
                    @endif
                </div>
                @if($epreuve->user && $epreuve->user->certifie)
                    <span class="badge bg-success" style="font-size:10px;">✔ Certifié</span>
                @endif
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body px-3 py-3">
            <h6 class="fw-bold mb-2">{{ $epreuve->titre }}</h6>
            <div class="d-flex flex-wrap gap-1 mb-2">
                <span class="badge bg-primary"><i class="fas fa-book me-1"></i>{{ $epreuve->matiere }}</span>
                <span class="badge bg-info text-dark">{{ $epreuve->classe }}</span>
                @if($epreuve->classe !== '3ème' && !empty($epreuve->serie) && trim($epreuve->serie) !== '')
                    <span class="badge badge-serie"><i class="fas fa-layer-group me-1"></i>Série {{ $epreuve->serie }}</span>
                @endif
                @if($epreuve->classe === '1ère')
                    <span class="badge bg-warning text-dark">Devoir</span>
                @else
                    <span class="badge bg-warning text-dark">{{ $epreuve->type_epreuve ?: ($epreuve->type ?: 'Épreuve') }}</span>
                @endif
                @if($epreuve->semestre && $epreuve->type_epreuve !== 'Examen Blanc')
                    <span class="badge bg-secondary">{{ $epreuve->semestre }}</span>
                @endif
            </div>
            @if($epreuve->description)
                <p class="small text-muted mb-2">{{ Str::limit($epreuve->description, 85) }}</p>
            @endif
            <div class="d-flex align-items-center justify-content-between mt-3">
                <p class="small text-muted mb-0">
                    <i class="fas fa-calendar me-1"></i>{{ $epreuve->created_at->format('d/m/Y') }}
                </p>
                <a href="{{ route('epreuves.show', $epreuve) }}?from=profil"
                   class="btn btn-outline-info btn-sm py-1 px-3">
                    <i class="fas fa-eye me-1"></i>Voir
                </a>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer bg-white border-top px-3 py-2">
            <div class="d-flex gap-2 mb-2">
                <!-- Bouton Partager -->
                <button class="btn btn-outline-primary btn-sm flex-fill share-btn"
                        data-share-url="{{ route('epreuves.download', $epreuve->id) }}"
                        onclick="copyShareLink(this); return false;">
                    <i class="fas fa-share-alt me-1"></i>Partager
                </button>

                <!-- Bouton Télécharger -->
                <a href="{{ route('epreuves.download', $epreuve->id) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-download me-1"></i>{{ $epreuve->downloads ?? 0 }}
                </a>

                <!-- Bouton Like -->
                <button type="button"
                        class="btn btn-sm like-toggle {{ in_array($epreuve->id, $likedIds ?? []) ? 'btn-danger' : 'btn-outline-danger' }}"
                        data-id="{{ $epreuve->id }}"
                        title="@if(in_array($epreuve->id, $likedIds ?? []))Je n'aime plus@elseJ'aime @endif">
                    <i class="fas fa-heart me-1"></i>
                    <span class="like-count">{{ $epreuve->likes_count ?? 0 }}</span>
                </button>

                <!-- Bouton Supprimer -->
                @auth
                @if(auth()->id() === $epreuve->user_id)
                <form action="{{ route('epreuves.destroy', $epreuve) }}" method="POST" style="display: inline;"
                      onsubmit="return confirm('⚠️ Supprimer définitivement cette épreuve ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                @endif
                @endauth
            </div>

            <div class="score-bar text-center" style="font-size:12px; background: linear-gradient(90deg, #28a745, #20c997); color: white; border-radius: 8px; padding: 4px 10px;">
                🏆 Score : {{ (($epreuve->likes_count ?? 0) * 2) + (($epreuve->downloads ?? 0) * 3) }}
            </div>
        </div>
    </div>
</div>