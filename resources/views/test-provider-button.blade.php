@extends('layouts.app')

@section('title', 'Test Bouton Prestataire')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-body">
            <h2>Test Bouton Prestataire</h2>
            <hr>
            
            <h4>Informations utilisateur :</h4>
            @auth
                <ul>
                    <li><strong>Nom :</strong> {{ Auth::user()->name }}</li>
                    <li><strong>User Type :</strong> {{ Auth::user()->user_type ?? 'NULL' }}</li>
                    <li><strong>Is Service Provider :</strong> {{ Auth::user()->is_service_provider ? 'OUI' : 'NON' }}</li>
                </ul>
                
                <h4>Conditions :</h4>
                <ul>
                    <li>user_type === 'particulier' : {{ Auth::user()->user_type === 'particulier' ? 'TRUE' : 'FALSE' }}</li>
                    <li>!is_service_provider : {{ !Auth::user()->is_service_provider ? 'TRUE' : 'FALSE' }}</li>
                    <li>Les deux conditions : {{ (Auth::user()->user_type === 'particulier' && !Auth::user()->is_service_provider) ? 'TRUE - BOUTON DEVRAIT APPARAÎTRE' : 'FALSE' }}</li>
                </ul>
                
                <h4>Test du bouton :</h4>
                @if(Auth::user()->user_type === 'particulier' && !Auth::user()->is_service_provider)
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#becomeProviderModal">
                        <i class="fas fa-user-plus"></i> Devenir prestataire (TEST)
                    </button>
                    <p class="text-success mt-2">✅ Le bouton s'affiche correctement !</p>
                @elseif(Auth::user()->is_service_provider && Auth::user()->user_type === 'particulier')
                    <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#becomeProviderModal">
                        <i class="fas fa-check-circle"></i> Prestataire (Gérer)
                    </button>
                    <p class="text-info mt-2">✅ Vous êtes déjà prestataire !</p>
                @else
                    <p class="text-danger">❌ Le bouton n'apparaît pas car les conditions ne sont pas remplies.</p>
                    <p>user_type = "{{ Auth::user()->user_type }}" (attendu: "particulier")</p>
                @endif
            @else
                <p class="text-warning">Non connecté</p>
            @endauth
        </div>
    </div>
</div>
@endsection
