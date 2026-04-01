@extends('admin.layouts.app')

@section('title', 'Modifier la Publicité')

@section('content')
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.advertisements') }}">Publicités</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
        <h2 class="h4 fw-bold">
            <i class="fas fa-edit text-primary me-2"></i>Modifier la Publicité
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               name="title" value="{{ old('title', $advertisement->title) }}" required maxlength="100">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" maxlength="255">{{ old('description', $advertisement->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lien (URL)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               name="link" value="{{ old('link', $advertisement->link) }}"
                               placeholder="https://exemple.com">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                            <select class="form-select @error('position') is-invalid @enderror" name="position" required>
                                <option value="sidebar" {{ old('position', $advertisement->position) === 'sidebar' ? 'selected' : '' }}>Sidebar (colonne droite)</option>
                                <option value="banner" {{ old('position', $advertisement->position) === 'banner' ? 'selected' : '' }}>Bannière (haut de page)</option>
                                <option value="popup" {{ old('position', $advertisement->position) === 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Priorité <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                   name="priority" value="{{ old('priority', $advertisement->priority) }}" min="0" max="100" required>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image</label>
                        @if($advertisement->image)
                            <div class="mb-2">
                                <img src="{{ storage_url($advertisement->image) }}" alt="{{ $advertisement->title }}" 
                                     style="max-width: 200px; border-radius: 8px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               name="image" accept="image/*">
                        <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de début</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   name="starts_at" value="{{ old('starts_at', $advertisement->starts_at ? $advertisement->starts_at->format('Y-m-d\TH:i') : '') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date de fin</label>
                            <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" 
                                   name="ends_at" value="{{ old('ends_at', $advertisement->ends_at ? $advertisement->ends_at->format('Y-m-d\TH:i') : '') }}">
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   {{ old('is_active', $advertisement->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Publicité active</strong>
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
        <!-- Statistiques de la publicité -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-info"></i>Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 mb-0 text-primary">{{ number_format($advertisement->impressions) }}</div>
                        <small class="text-muted">Impressions</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 mb-0 text-success">{{ number_format($advertisement->clicks) }}</div>
                        <small class="text-muted">Clics</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h5 mb-0">
                        @if($advertisement->impressions > 0)
                            {{ number_format(($advertisement->clicks / $advertisement->impressions) * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <small class="text-muted">Taux de clic (CTR)</small>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-clock me-2 text-secondary"></i>Informations</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <small class="text-muted">Créée le:</small><br>
                    {{ $advertisement->created_at->format('d/m/Y à H:i') }}
                </p>
                <p class="mb-0">
                    <small class="text-muted">Dernière modification:</small><br>
                    {{ $advertisement->updated_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
