<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Carte Publication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8fafc;
            padding: 40px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .container-test {
            max-width: 650px;
            margin: 0 auto;
        }
        
        /* =========================================
           CARTE PUBLICATION (MISSION) - Style Facebook/LinkedIn
           ========================================= */
        .mission-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .mission-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .mission-card-header {
            display: flex;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .mission-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .mission-user-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3a86ff, #8338ec);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .mission-user-info {
            flex: 1;
        }

        .mission-user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .mission-meta {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* =========================================
           BOUTON 3 POINTS (MENU)
           ========================================= */
        .btn-three-dots {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            color: #65676b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-three-dots:hover {
            background: #f0f2f5;
        }

        .btn-three-dots i {
            font-size: 1rem;
        }

        .mission-card-body {
            padding: 16px;
        }

        .mission-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .mission-description {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .mission-badges {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .mission-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mission-badge-category {
            background: #eff6ff;
            color: #3a86ff;
        }

        .mission-badge-urgent {
            background: #fef2f2;
            color: #dc2626;
        }

        .mission-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #059669;
        }

        /* Stats */
        .mission-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 16px;
            color: #65676b;
            font-size: 0.85rem;
            border-top: 1px solid #f1f5f9;
        }

        .mission-stats span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* =========================================
           BOUTONS ACTIONS SOCIALES
           ========================================= */
        .mission-actions {
            display: flex;
            justify-content: center;
            gap: 24px;
            padding: 12px 16px;
            border-top: 1px solid #f1f5f9;
            background: white;
        }

        .mission-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border: none;
            background: #f0f2f5;
            color: #65676b;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 20px;
            transition: all 0.15s;
        }

        .mission-action-btn:hover {
            background: #e4e6eb;
            color: #3a86ff;
        }

        .mission-action-btn i {
            font-size: 1rem;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #1e293b;
        }

        .success-badge {
            background: #dcfce7;
            color: #166534;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container-test">
        <h1>✅ Test des éléments de publication</h1>
        
        <div class="success-badge">
            <i class="fas fa-check-circle me-2"></i>
            Les éléments ci-dessous devraient apparaître sur la page /feed
        </div>

        <!-- Carte de publication test -->
        <div class="mission-card">
            <!-- En-tête avec avatar et menu 3 points -->
            <div class="mission-card-header">
                <div class="mission-user-avatar">
                    <div class="mission-user-avatar-placeholder">J</div>
                </div>
                <div class="mission-user-info">
                    <div class="mission-user-name">Jean Dupont</div>
                    <div class="mission-meta">
                        <span>Il y a 2 heures</span>
                        <span>·</span>
                        <span><i class="fas fa-map-marker-alt me-1"></i>Paris</span>
                    </div>
                </div>
                <!-- MENU 3 POINTS -->
                <div class="dropdown ms-auto">
                    <button class="btn-three-dots" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2 text-primary"></i>Voir les détails</a></li>
                        <li><a class="dropdown-item" href="#"><i class="far fa-bookmark me-2 text-warning"></i>Sauvegarder</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 text-info"></i>Voir le profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-flag me-2"></i>Signaler la publication</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye-slash me-2 text-muted"></i>Masquer cette annonce</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contenu -->
            <div class="mission-card-body">
                <h4 class="mission-title">Recherche plombier pour réparation fuite</h4>
                <p class="mission-description">J'ai besoin d'un plombier expérimenté pour réparer une fuite sous l'évier de ma cuisine. Intervention urgente souhaitée.</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mission-badges">
                        <span class="mission-badge mission-badge-category">Plomberie</span>
                        <span class="mission-badge mission-badge-urgent"><i class="fas fa-bolt me-1"></i>Urgent</span>
                    </div>
                    <span class="mission-price">150 €</span>
                </div>
            </div>

            <!-- Stats -->
            <div class="mission-stats">
                <span><i class="far fa-eye me-1"></i>45 vues</span>
                <span><i class="far fa-comment me-1"></i>3 messages</span>
            </div>

            <!-- BOUTONS COMMENTER ET PARTAGER -->
            <div class="mission-actions">
                <button class="mission-action-btn">
                    <i class="far fa-comment"></i>
                    <span>Commenter</span>
                </button>
                <button class="mission-action-btn">
                    <i class="fas fa-share"></i>
                    <span>Partager</span>
                </button>
            </div>
        </div>

        <!-- Deuxième carte -->
        <div class="mission-card">
            <div class="mission-card-header">
                <div class="mission-user-avatar">
                    <div class="mission-user-avatar-placeholder">M</div>
                </div>
                <div class="mission-user-info">
                    <div class="mission-user-name">Marie Martin</div>
                    <div class="mission-meta">
                        <span>Hier</span>
                        <span>·</span>
                        <span><i class="fas fa-map-marker-alt me-1"></i>Lyon</span>
                    </div>
                </div>
                <div class="dropdown ms-auto">
                    <button class="btn-three-dots" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2 text-primary"></i>Voir les détails</a></li>
                        <li><a class="dropdown-item" href="#"><i class="far fa-bookmark me-2 text-warning"></i>Sauvegarder</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 text-info"></i>Voir le profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-flag me-2"></i>Signaler la publication</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye-slash me-2 text-muted"></i>Masquer cette annonce</a></li>
                    </ul>
                </div>
            </div>

            <div class="mission-card-body">
                <h4 class="mission-title">Cours de piano pour débutant</h4>
                <p class="mission-description">Je propose des cours de piano à domicile pour débutants. 10 ans d'expérience, pédagogie adaptée.</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mission-badges">
                        <span class="mission-badge mission-badge-category">Cours & Formation</span>
                    </div>
                    <span class="mission-price">35 €/h</span>
                </div>
            </div>

            <div class="mission-stats">
                <span><i class="far fa-eye me-1"></i>128 vues</span>
                <span><i class="far fa-comment me-1"></i>12 messages</span>
            </div>

            <div class="mission-actions">
                <button class="mission-action-btn">
                    <i class="far fa-comment"></i>
                    <span>Commenter</span>
                </button>
                <button class="mission-action-btn">
                    <i class="fas fa-share"></i>
                    <span>Partager</span>
                </button>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <strong>Légende :</strong><br>
            <i class="fas fa-ellipsis-h"></i> = Menu 3 points (en haut à droite de chaque carte)<br>
            <i class="far fa-comment"></i> Commenter + <i class="fas fa-share"></i> Partager = Boutons en bas de chaque carte
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
