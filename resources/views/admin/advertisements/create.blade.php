@extends('admin.layouts.app')

@section('title', 'Créer une Publicité')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.advertisements') }}">Publicités</a></li>
                <li class="breadcrumb-item active">Nouvelle publicité</li>
            </ol>
        </nav>
        <h2 class="h4 fw-bold">
            <i class="fas fa-plus-circle text-primary me-2"></i>Créer une Publicité
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               name="title" value="{{ old('title') }}" required maxlength="100"
                               placeholder="Ex: Découvrez nos services premium">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" maxlength="255"
                                  placeholder="Description courte de la publicité">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lien (URL)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               name="link" value="{{ old('link') }}"
                               placeholder="https://exemple.com">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                            <select class="form-select @error('position') is-invalid @enderror" name="position" required>
                                <option value="sidebar" {{ old('position') === 'sidebar' ? 'selected' : '' }}>Sidebar (colonne droite)</option>
                                <option value="banner" {{ old('position') === 'banner' ? 'selected' : '' }}>Bannière (haut de page)</option>
                                <option value="popup" {{ old('position') === 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Priorité <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                   name="priority" value="{{ old('priority', 0) }}" min="0" max="100" required>
                            <small class="text-muted">Plus le chiffre est élevé, plus la publicité sera prioritaire</small>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               name="image" accept="image/*">
                        <small class="text-muted">Formats acceptés: JPEG, PNG, GIF, WebP. Max 2 Mo.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de début</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   name="starts_at" value="{{ old('starts_at') }}">
                            <small class="text-muted">Laisser vide pour commencer immédiatement</small>
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de fin</label>
                            <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" 
                                   name="ends_at" value="{{ old('ends_at') }}">
                            <small class="text-muted">Laisser vide pour une durée illimitée</small>
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Activer immédiatement</strong>
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Créer la publicité
                        </button>
                        <a href="{{ route('admin.advertisements') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>Guide des positions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-info">Sidebar</strong>
                    <p class="small text-muted mb-0">Affichée dans la colonne droite de la page d'accueil. Format recommandé: 280x150px</p>
                </div>
                <div class="mb-3">
                    <strong class="text-primary">Bannière</strong>
                    <p class="small text-muted mb-0">Affichée en haut des pages principales. Format recommandé: 728x90px</p>
                </div>
                <div>
                    <strong class="text-warning">Popup</strong>
                    <p class="small text-muted mb-0">Affichée en superposition. Format recommandé: 400x300px</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
