

<?php $__env->startSection('title', 'Détail vérification - ' . ($verification->user->name ?? 'Utilisateur')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .doc-review-card { border: 2px solid #e2e8f0; border-radius: 16px; overflow: hidden; transition: all 0.2s; }
    .doc-review-card.status-approved { border-color: #10b981; }
    .doc-review-card.status-rejected { border-color: #ef4444; }
    .doc-review-card.status-pending { border-color: #f59e0b; }
    .doc-review-header { padding: 12px 16px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; justify-content: space-between; }
    .doc-review-header.bg-approved { background: #dcfce7; color: #166534; }
    .doc-review-header.bg-rejected { background: #fee2e2; color: #991b1b; }
    .doc-review-header.bg-pending { background: #fef3c7; color: #92400e; }
    .doc-status-select { border-radius: 8px; padding: 6px 12px; font-size: 0.85rem; font-weight: 600; border: 2px solid #e2e8f0; }
    .doc-status-select.val-approved { border-color: #10b981; color: #166534; background: #dcfce7; }
    .doc-status-select.val-rejected { border-color: #ef4444; color: #991b1b; background: #fee2e2; }
    .doc-status-select.val-pending { border-color: #f59e0b; color: #92400e; background: #fef3c7; }
    .rejection-reason-field { display: none; margin-top: 10px; }
    .rejection-reason-field.visible { display: block; }
</style>

<div class="row mb-4">
    <div class="col">
        <a href="<?php echo e(route('admin.verifications')); ?>" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Retour aux vérifications
        </a>
        <h2 class="h4 fw-bold mt-2">
            <i class="fas fa-shield-alt me-2 text-primary"></i>Demande de vérification #<?php echo e($verification->id); ?>

        </h2>
    </div>
    <div class="col-auto d-flex align-items-end gap-2">
        <?php if($verification->isPending() || $verification->isReturned()): ?>
            <form action="<?php echo e(route('admin.verifications.approve', $verification->id)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success" onclick="return confirm('Approuver TOUS les documents et vérifier le profil ?')">
                    <i class="fas fa-check-double me-1"></i> Tout approuver
                </button>
            </form>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#returnModal">
                <i class="fas fa-undo-alt me-1"></i> Renvoyer à l'utilisateur
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="fas fa-times me-1"></i> Tout rejeter
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Informations générales -->
    <div class="col-lg-4">
        <!-- Statut -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <?php if($verification->status === 'pending' && $verification->isResubmission()): ?>
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(16, 185, 129, 0.15);">
                        <i class="fas fa-sync-alt fa-2x" style="color: #10b981;"></i>
                    </div>
                    <h5 class="text-success mb-1"><i class="fas fa-bell me-1"></i> Documents corrigés reçus !</h5>
                    <small class="text-muted">L'utilisateur a resoumis ses documents le <?php echo e($verification->resubmitted_at ? $verification->resubmitted_at->format('d/m/Y à H:i') : '-'); ?></small>
                    <div class="alert mt-3 mb-0 py-2 small" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                        <i class="fas fa-info-circle me-1"></i>
                        Resoumission n°<?php echo e($verification->resubmission_count); ?> — Veuillez réexaminer les documents mis à jour
                    </div>
                <?php elseif($verification->status === 'pending'): ?>
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(245, 158, 11, 0.15);">
                        <i class="fas fa-clock fa-2x" style="color: #f59e0b;"></i>
                    </div>
                    <h5 class="text-warning mb-1">En attente de vérification</h5>
                    <small class="text-muted">Nouvelle demande — attend votre examen</small>
                <?php elseif($verification->status === 'returned'): ?>
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(245, 158, 11, 0.15);">
                        <i class="fas fa-undo fa-2x" style="color: #f59e0b;"></i>
                    </div>
                    <h5 style="color: #f59e0b;" class="mb-1"><i class="fas fa-undo me-1"></i> Renvoyé à l'utilisateur</h5>
                    <small class="text-muted">En attente de correction par l'utilisateur</small>
                    <?php if($verification->reviewed_at): ?>
                        <div class="mt-2"><small class="text-muted">Renvoyé le <?php echo e($verification->reviewed_at->format('d/m/Y à H:i')); ?></small></div>
                    <?php endif; ?>
                    <?php if($verification->admin_message): ?>
                        <div class="alert alert-info mt-3 mb-0 text-start small">
                            <strong>Message admin :</strong> <?php echo e($verification->admin_message); ?>

                        </div>
                    <?php endif; ?>
                <?php elseif($verification->status === 'approved'): ?>
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(16, 185, 129, 0.15);">
                        <i class="fas fa-check-circle fa-2x" style="color: #10b981;"></i>
                    </div>
                    <h5 class="text-success mb-1">Approuvée</h5>
                    <small class="text-muted">
                        Approuvée le <?php echo e($verification->reviewed_at ? $verification->reviewed_at->format('d/m/Y à H:i') : '-'); ?>

                        <?php if($verification->reviewer): ?>
                            par <?php echo e($verification->reviewer->name); ?>

                        <?php endif; ?>
                    </small>
                <?php elseif($verification->status === 'rejected'): ?>
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.15);">
                        <i class="fas fa-times-circle fa-2x" style="color: #ef4444;"></i>
                    </div>
                    <h5 class="text-danger mb-1">Rejetée</h5>
                    <small class="text-muted">
                        Rejetée le <?php echo e($verification->reviewed_at ? $verification->reviewed_at->format('d/m/Y à H:i') : '-'); ?>

                    </small>
                    <?php if($verification->rejection_reason): ?>
                        <div class="alert alert-danger mt-3 mb-0 text-start">
                            <strong>Motif :</strong> <?php echo e($verification->rejection_reason); ?>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informations utilisateur -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Utilisateur</h6>
            </div>
            <div class="card-body">
                <?php if($verification->user): ?>
                    <div class="text-center mb-3">
                        <?php if($verification->user->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . $verification->user->avatar)); ?>" alt="" class="rounded-circle mb-2" style="width: 72px; height: 72px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 72px; height: 72px; font-size: 1.5rem; font-weight: 600;">
                                <?php echo e(strtoupper(substr($verification->user->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                        <h6 class="mb-0"><?php echo e($verification->user->name); ?></h6>
                        <small class="text-muted"><?php echo e($verification->user->email); ?></small>
                    </div>

                    <hr>

                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type de compte</span>
                            <span class="fw-semibold">
                                <?php if($verification->user->isProfessionnel()): ?>
                                    <span class="badge bg-primary">Professionnel</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Particulier</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if($verification->user->business_type): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Statut pro</span>
                            <span class="fw-semibold"><?php echo e($verification->user->business_type === 'entreprise' ? 'Entreprise' : 'Auto-entrepreneur'); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($verification->user->profession): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Métier</span>
                            <span class="fw-semibold"><?php echo e($verification->user->profession); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($verification->user->city): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Localisation</span>
                            <span class="fw-semibold"><?php echo e($verification->user->city); ?><?php echo e($verification->user->country ? ', ' . $verification->user->country : ''); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Inscription</span>
                            <span class="fw-semibold"><?php echo e($verification->user->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Vérifié</span>
                            <span>
                                <?php if($verification->user->is_verified): ?>
                                    <i class="fas fa-check text-success"></i> Oui
                                <?php else: ?>
                                    <i class="fas fa-times text-danger"></i> Non
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    
                    <?php
                        $missingFields = [];
                        if (!$verification->user->name) $missingFields[] = 'Nom';
                        if (!$verification->user->phone) $missingFields[] = 'Téléphone';
                        if (!$verification->user->city) $missingFields[] = 'Ville';
                        if (!$verification->user->country) $missingFields[] = 'Pays';
                        if (!$verification->user->address) $missingFields[] = 'Adresse';
                        if ($verification->user->isProfessionnel()) {
                            if (!$verification->user->profession) $missingFields[] = 'Profession';
                            if (!$verification->user->business_type) $missingFields[] = 'Type d\'activité (entreprise/auto-entrepreneur)';
                        }
                    ?>
                    <?php if(count($missingFields) > 0): ?>
                        <div class="alert alert-warning mt-3 mb-0 small">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Profil incomplet :</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                <?php $__currentLoopData = $missingFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($field); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="<?php echo e(route('admin.users.show', $verification->user->id)); ?>" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-external-link-alt me-1"></i> Voir le profil complet
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Utilisateur supprimé</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Détails de la demande -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-info"></i>Détails de la demande</h6>
            </div>
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Type</span>
                    <span class="fw-semibold">
                        <?php if($verification->type === 'profile_verification'): ?>
                            Vérification de profil
                        <?php else: ?>
                            Vérification prestataire
                        <?php endif; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Document</span>
                    <span class="fw-semibold">
                        <?php
                            $docTypes = ['id_card' => 'Carte d\'identité', 'passport' => 'Passeport', 'driver_license' => 'Permis de conduire', 'cni' => 'CNI', 'permis' => 'Permis', 'carte_sejour' => 'Carte de séjour'];
                        ?>
                        <?php echo e($docTypes[$verification->document_type] ?? $verification->document_type); ?>

                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Montant payé</span>
                    <span class="fw-semibold text-success"><?php echo e(number_format($verification->payment_amount, 2)); ?>€</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Paiement</span>
                    <span>
                        <?php if($verification->isPaid()): ?>
                            <span class="badge bg-success">Payé</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Soumis le</span>
                    <span class="fw-semibold"><?php echo e($verification->submitted_at ? $verification->submitted_at->format('d/m/Y H:i') : ($verification->created_at ? $verification->created_at->format('d/m/Y H:i') : '-')); ?></span>
                </div>
                <?php if($verification->resubmission_count > 0): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Resoumis le</span>
                    <span class="fw-semibold text-success">
                        <i class="fas fa-sync-alt me-1"></i><?php echo e($verification->resubmitted_at ? $verification->resubmitted_at->format('d/m/Y H:i') : '-'); ?>

                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Nb resoumissions</span>
                    <span class="badge bg-info"><?php echo e($verification->resubmission_count); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Documents avec revue individuelle -->
    <div class="col-lg-8">
        <?php if($verification->isPending() || $verification->isReturned()): ?>
        <form action="<?php echo e(route('admin.verifications.review-documents', $verification->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-image me-2 text-success"></i>Documents soumis — Revue individuelle</h6>
                <?php if($verification->isPending() || $verification->isReturned()): ?>
                    <span class="badge bg-info text-white">Évaluez chaque document</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    
                    <?php $fs = $verification->document_front_status ?? 'pending'; ?>
                    <div class="col-md-6">
                        <div class="doc-review-card status-<?php echo e($fs); ?>">
                            <div class="doc-review-header bg-<?php echo e($fs); ?>">
                                <span><i class="fas fa-id-card me-2"></i>Recto du document</span>
                                <?php if($fs === 'approved'): ?><span class="badge bg-success">Validé</span>
                                <?php elseif($fs === 'rejected'): ?><span class="badge bg-danger">Rejeté</span>
                                <?php else: ?><span class="badge bg-warning text-dark">En attente</span><?php endif; ?>
                            </div>
                            <?php if($verification->document_front): ?>
                                <div style="background: #f8f9fa; min-height: 200px;">
                                    <?php $frontExt = strtolower(pathinfo($verification->document_front, PATHINFO_EXTENSION)); ?>
                                    <?php if(in_array($frontExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <img src="<?php echo e(asset('storage/' . $verification->document_front)); ?>" 
                                             alt="Document recto" class="img-fluid w-100" 
                                             style="cursor: pointer; object-fit: contain; max-height: 250px;"
                                             onclick="openImageModal(this.src, 'Recto du document')">
                                    <?php else: ?>
                                        <div class="p-4 text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                                            <a href="<?php echo e(asset('storage/' . $verification->document_front)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>Ouvrir le document PDF
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="p-4 text-center text-muted" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div><i class="fas fa-image fa-2x mb-2 d-block"></i><small>Non fourni</small></div>
                                </div>
                            <?php endif; ?>
                            <?php if($verification->isPending() || $verification->isReturned()): ?>
                            <div class="p-3 border-top">
                                <select name="document_front_status" class="form-select doc-status-select val-<?php echo e($fs); ?>" onchange="toggleReasonField(this, 'front_reason'); updateSelectStyle(this)">
                                    <option value="pending" <?php echo e($fs === 'pending' ? 'selected' : ''); ?>>⏳ En attente</option>
                                    <option value="approved" <?php echo e($fs === 'approved' ? 'selected' : ''); ?>>✅ Validé</option>
                                    <option value="rejected" <?php echo e($fs === 'rejected' ? 'selected' : ''); ?>>❌ Rejeté</option>
                                </select>
                                <div class="rejection-reason-field <?php echo e($fs === 'rejected' ? 'visible' : ''); ?>" id="front_reason">
                                    <textarea name="document_front_rejection_reason" class="form-control form-control-sm" rows="2" placeholder="Raison du rejet..."><?php echo e($verification->document_front_rejection_reason); ?></textarea>
                                </div>
                            </div>
                            <?php elseif($fs === 'rejected' && $verification->document_front_rejection_reason): ?>
                            <div class="p-3 border-top bg-light">
                                <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($verification->document_front_rejection_reason); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <?php $bs = $verification->document_back_status ?? 'pending'; ?>
                    <div class="col-md-6">
                        <div class="doc-review-card status-<?php echo e($bs); ?>">
                            <div class="doc-review-header bg-<?php echo e($bs); ?>">
                                <span><i class="fas fa-id-card me-2"></i>Verso du document</span>
                                <?php if($verification->document_back): ?>
                                    <?php if($bs === 'approved'): ?><span class="badge bg-success">Validé</span>
                                    <?php elseif($bs === 'rejected'): ?><span class="badge bg-danger">Rejeté</span>
                                    <?php else: ?><span class="badge bg-warning text-dark">En attente</span><?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if($verification->document_back): ?>
                                <div style="background: #f8f9fa; min-height: 200px;">
                                    <?php $backExt = strtolower(pathinfo($verification->document_back, PATHINFO_EXTENSION)); ?>
                                    <?php if(in_array($backExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <img src="<?php echo e(asset('storage/' . $verification->document_back)); ?>" 
                                             alt="Document verso" class="img-fluid w-100" 
                                             style="cursor: pointer; object-fit: contain; max-height: 250px;"
                                             onclick="openImageModal(this.src, 'Verso du document')">
                                    <?php else: ?>
                                        <div class="p-4 text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                                            <a href="<?php echo e(asset('storage/' . $verification->document_back)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>Ouvrir le document PDF
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if($verification->isPending() || $verification->isReturned()): ?>
                                <div class="p-3 border-top">
                                    <select name="document_back_status" class="form-select doc-status-select val-<?php echo e($bs); ?>" onchange="toggleReasonField(this, 'back_reason'); updateSelectStyle(this)">
                                        <option value="pending" <?php echo e($bs === 'pending' ? 'selected' : ''); ?>>⏳ En attente</option>
                                        <option value="approved" <?php echo e($bs === 'approved' ? 'selected' : ''); ?>>✅ Validé</option>
                                        <option value="rejected" <?php echo e($bs === 'rejected' ? 'selected' : ''); ?>>❌ Rejeté</option>
                                    </select>
                                    <div class="rejection-reason-field <?php echo e($bs === 'rejected' ? 'visible' : ''); ?>" id="back_reason">
                                        <textarea name="document_back_rejection_reason" class="form-control form-control-sm" rows="2" placeholder="Raison du rejet..."><?php echo e($verification->document_back_rejection_reason); ?></textarea>
                                    </div>
                                </div>
                                <?php elseif($bs === 'rejected' && $verification->document_back_rejection_reason): ?>
                                <div class="p-3 border-top bg-light">
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($verification->document_back_rejection_reason); ?></small>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="p-4 text-center text-muted" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div><i class="fas fa-image fa-2x mb-2 d-block"></i><small>Non fourni</small></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <?php $ss = $verification->selfie_status ?? 'pending'; ?>
                    <div class="col-md-6">
                        <div class="doc-review-card status-<?php echo e($ss); ?>">
                            <div class="doc-review-header bg-<?php echo e($ss); ?>">
                                <span><i class="fas fa-camera me-2"></i>Selfie de vérification</span>
                                <?php if($ss === 'approved'): ?><span class="badge bg-success">Validé</span>
                                <?php elseif($ss === 'rejected'): ?><span class="badge bg-danger">Rejeté</span>
                                <?php else: ?><span class="badge bg-warning text-dark">En attente</span><?php endif; ?>
                            </div>
                            <?php if($verification->selfie): ?>
                                <div style="background: #f8f9fa; min-height: 200px;">
                                    <?php $selfieExt = strtolower(pathinfo($verification->selfie, PATHINFO_EXTENSION)); ?>
                                    <?php if(in_array($selfieExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <img src="<?php echo e(asset('storage/' . $verification->selfie)); ?>" 
                                             alt="Selfie" class="img-fluid w-100" 
                                             style="cursor: pointer; object-fit: contain; max-height: 250px;"
                                             onclick="openImageModal(this.src, 'Selfie de vérification')">
                                    <?php else: ?>
                                        <div class="p-4 text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                                            <a href="<?php echo e(asset('storage/' . $verification->selfie)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>Ouvrir le document PDF
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="p-4 text-center text-muted" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div><i class="fas fa-image fa-2x mb-2 d-block"></i><small>Non fourni</small></div>
                                </div>
                            <?php endif; ?>
                            <?php if($verification->isPending() || $verification->isReturned()): ?>
                            <div class="p-3 border-top">
                                <select name="selfie_status" class="form-select doc-status-select val-<?php echo e($ss); ?>" onchange="toggleReasonField(this, 'selfie_reason'); updateSelectStyle(this)">
                                    <option value="pending" <?php echo e($ss === 'pending' ? 'selected' : ''); ?>>⏳ En attente</option>
                                    <option value="approved" <?php echo e($ss === 'approved' ? 'selected' : ''); ?>>✅ Validé</option>
                                    <option value="rejected" <?php echo e($ss === 'rejected' ? 'selected' : ''); ?>>❌ Rejeté</option>
                                </select>
                                <div class="rejection-reason-field <?php echo e($ss === 'rejected' ? 'visible' : ''); ?>" id="selfie_reason">
                                    <textarea name="selfie_rejection_reason" class="form-control form-control-sm" rows="2" placeholder="Raison du rejet..."><?php echo e($verification->selfie_rejection_reason); ?></textarea>
                                </div>
                            </div>
                            <?php elseif($ss === 'rejected' && $verification->selfie_rejection_reason): ?>
                            <div class="p-3 border-top bg-light">
                                <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($verification->selfie_rejection_reason); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <?php if($verification->professional_document || ($verification->user && $verification->user->isProfessionnel())): ?>
                    <?php $ps = $verification->professional_document_status ?? 'pending'; ?>
                    <div class="col-md-6">
                        <div class="doc-review-card status-<?php echo e($ps); ?>">
                            <div class="doc-review-header bg-<?php echo e($ps); ?>">
                                <span>
                                    <i class="fas fa-building me-2"></i>
                                    <?php if($verification->professional_document_type === 'kbis'): ?>
                                        Extrait Kbis
                                    <?php elseif($verification->professional_document_type === 'sirene'): ?>
                                        Avis de situation SIRENE
                                    <?php else: ?>
                                        Document professionnel
                                        <?php if($verification->user && $verification->user->isEntreprise()): ?>
                                            <small>(Kbis attendu)</small>
                                        <?php elseif($verification->user && $verification->user->isAutoEntrepreneur()): ?>
                                            <small>(SIRENE attendu)</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                                <?php if($verification->professional_document): ?>
                                    <?php if($ps === 'approved'): ?><span class="badge bg-success">Validé</span>
                                    <?php elseif($ps === 'rejected'): ?><span class="badge bg-danger">Rejeté</span>
                                    <?php else: ?><span class="badge bg-warning text-dark">En attente</span><?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non fourni</span>
                                <?php endif; ?>
                            </div>
                            <?php if($verification->professional_document): ?>
                                <div style="background: #f8f9fa; min-height: 200px;">
                                    <?php
                                        $ext = strtolower(pathinfo($verification->professional_document, PATHINFO_EXTENSION));
                                    ?>
                                    <?php if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <img src="<?php echo e(asset('storage/' . $verification->professional_document)); ?>" 
                                             alt="Document pro" class="img-fluid w-100" 
                                             style="cursor: pointer; object-fit: contain; max-height: 250px;"
                                             onclick="openImageModal(this.src, 'Document professionnel')">
                                    <?php else: ?>
                                        <div class="p-4 text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                                            <a href="<?php echo e(asset('storage/' . $verification->professional_document)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>Télécharger le document
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if($verification->isPending() || $verification->isReturned()): ?>
                                <div class="p-3 border-top">
                                    <select name="professional_document_status" class="form-select doc-status-select val-<?php echo e($ps); ?>" onchange="toggleReasonField(this, 'pro_reason'); updateSelectStyle(this)">
                                        <option value="pending" <?php echo e($ps === 'pending' ? 'selected' : ''); ?>>⏳ En attente</option>
                                        <option value="approved" <?php echo e($ps === 'approved' ? 'selected' : ''); ?>>✅ Validé</option>
                                        <option value="rejected" <?php echo e($ps === 'rejected' ? 'selected' : ''); ?>>❌ Rejeté</option>
                                    </select>
                                    <div class="rejection-reason-field <?php echo e($ps === 'rejected' ? 'visible' : ''); ?>" id="pro_reason">
                                        <textarea name="professional_document_rejection_reason" class="form-control form-control-sm" rows="2" placeholder="Raison du rejet..."><?php echo e($verification->professional_document_rejection_reason); ?></textarea>
                                    </div>
                                </div>
                                <?php elseif($ps === 'rejected' && $verification->professional_document_rejection_reason): ?>
                                <div class="p-3 border-top bg-light">
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($verification->professional_document_rejection_reason); ?></small>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="p-4 text-center text-muted" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div>
                                        <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                        <small>Document non fourni par l'utilisateur</small>
                                        <?php if($verification->user && $verification->user->isProfessionnel()): ?>
                                            <br><small class="text-danger fw-bold">⚠ Ce document est requis pour les professionnels</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                
                <?php if($verification->isPending() || $verification->isReturned()): ?>
                <hr class="my-4">
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fas fa-comment-alt me-2 text-primary"></i>Message à l'utilisateur (optionnel)</label>
                    <textarea name="admin_message" class="form-control" rows="3" placeholder="Ajoutez un message pour expliquer à l'utilisateur ce qui doit être corrigé..."><?php echo e($verification->admin_message); ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="fas fa-clipboard-check me-2"></i>Enregistrer la revue des documents
                    </button>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Si tous les documents sont validés, le profil sera automatiquement approuvé. 
                    Si un ou plusieurs documents sont rejetés, le formulaire sera renvoyé à l'utilisateur.
                </small>
                <?php endif; ?>
            </div>
        </div>

        <?php if($verification->isPending() || $verification->isReturned()): ?>
        </form>
        <?php endif; ?>

        <!-- Guide de vérification -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-check me-2 text-info"></i>Points de vérification</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Le document est lisible et non tronqué</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Les informations correspondent au profil</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Le document n'est pas expiré</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Le selfie correspond à la photo du document</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Aucune modification ou falsification visible</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-muted me-2"></i>Kbis/SIRENE valide pour les professionnels</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de renvoi à l'utilisateur -->
<?php if($verification->isPending() || $verification->isReturned()): ?>
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <h5 class="modal-title"><i class="fas fa-undo-alt me-2"></i>Renvoyer à l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.verifications.return', $verification->id)); ?>" method="POST" id="returnForm">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="document_front_status" id="return_document_front_status">
                <input type="hidden" name="document_front_rejection_reason" id="return_document_front_rejection_reason">
                <input type="hidden" name="document_back_status" id="return_document_back_status">
                <input type="hidden" name="document_back_rejection_reason" id="return_document_back_rejection_reason">
                <input type="hidden" name="selfie_status" id="return_selfie_status">
                <input type="hidden" name="selfie_rejection_reason" id="return_selfie_rejection_reason">
                <input type="hidden" name="professional_document_status" id="return_professional_document_status">
                <input type="hidden" name="professional_document_rejection_reason" id="return_professional_document_rejection_reason">
                <div class="modal-body">
                    <p>Vous allez renvoyer le formulaire de vérification à <strong><?php echo e($verification->user->name ?? 'cet utilisateur'); ?></strong> pour qu'il puisse corriger ou compléter sa demande.</p>
                    
                    <div class="alert alert-info small" id="returnModalDocSummary">
                        <i class="fas fa-info-circle me-1"></i>
                        Les statuts des documents que vous avez sélectionnés dans la revue seront enregistrés. L'utilisateur devra resoumettre les documents non validés.
                    </div>

                    <div class="mb-3">
                        <label for="return_admin_message" class="form-label fw-semibold">Message à l'utilisateur <span class="text-danger">*</span></label>
                        <textarea name="admin_message" id="return_admin_message" class="form-control" rows="4" required placeholder="Expliquez à l'utilisateur ce qu'il doit corriger..."></textarea>
                        <small class="text-muted">Ce message sera affiché sur la page de vérification de l'utilisateur.</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('return_admin_message').value = 'Certains de vos documents ne sont pas conformes. Veuillez les remplacer par des versions lisibles et valides.'">Documents non conformes</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('return_admin_message').value = 'Votre selfie de vérification n\'est pas assez clair. Veuillez reprendre la photo dans de bonnes conditions de luminosité.'">Selfie à refaire</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('return_admin_message').value = 'Veuillez fournir votre document professionnel (Kbis ou avis SIRENE) pour compléter votre vérification.'">Doc pro requis</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo-alt me-1"></i> Renvoyer le formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal de rejet global -->
<?php if($verification->isPending() || $verification->isReturned()): ?>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Rejeter la vérification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.verifications.reject', $verification->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Vous êtes sur le point de rejeter <strong>tous les documents</strong> de la demande de vérification de <strong><?php echo e($verification->user->name ?? 'cet utilisateur'); ?></strong>.</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-semibold">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required placeholder="Expliquez pourquoi la vérification est rejetée..."></textarea>
                        <small class="text-muted">Ce motif sera visible par l'utilisateur.</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('rejection_reason').value = 'Document illisible ou trop flou. Veuillez soumettre des images de meilleure qualité.'">Document illisible</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('rejection_reason').value = 'Le selfie ne correspond pas à la photo du document d\'identité.'">Selfie non concordant</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('rejection_reason').value = 'Document expiré. Veuillez fournir un document d\'identité valide.'">Document expiré</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('rejection_reason').value = 'Les informations du document ne correspondent pas aux informations du profil.'">Infos incorrectes</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('rejection_reason').value = 'Document professionnel (Kbis/SIRENE) manquant ou invalide.'">Doc pro manquant</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal d'image agrandie -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h6 class="modal-title text-white" id="imageModalTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" id="imageModalImg" alt="" class="img-fluid" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(src, title) {
    document.getElementById('imageModalImg').src = src;
    document.getElementById('imageModalTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function toggleReasonField(select, reasonId) {
    const reasonField = document.getElementById(reasonId);
    if (reasonField) {
        if (select.value === 'rejected') {
            reasonField.classList.add('visible');
        } else {
            reasonField.classList.remove('visible');
        }
    }
}

function updateSelectStyle(select) {
    select.className = 'form-select doc-status-select val-' + select.value;
}

// Sync review form selects into return modal hidden fields when modal opens
document.addEventListener('DOMContentLoaded', function() {
    var returnModal = document.getElementById('returnModal');
    if (returnModal) {
        returnModal.addEventListener('show.bs.modal', function() {
            var fields = ['document_front_status', 'document_back_status', 'selfie_status', 'professional_document_status'];
            var reasonFields = ['document_front_rejection_reason', 'document_back_rejection_reason', 'selfie_rejection_reason', 'professional_document_rejection_reason'];
            
            var approvedCount = 0;
            var rejectedCount = 0;
            
            fields.forEach(function(field) {
                var select = document.querySelector('select[name="' + field + '"]');
                var hiddenField = document.getElementById('return_' + field);
                if (select && hiddenField) {
                    hiddenField.value = select.value;
                    if (select.value === 'approved') approvedCount++;
                    if (select.value === 'rejected') rejectedCount++;
                } else if (hiddenField) {
                    hiddenField.value = '';
                }
            });
            
            reasonFields.forEach(function(field) {
                var textarea = document.querySelector('textarea[name="' + field + '"]');
                var hiddenField = document.getElementById('return_' + field);
                if (textarea && hiddenField) {
                    hiddenField.value = textarea.value;
                } else if (hiddenField) {
                    hiddenField.value = '';
                }
            });
            
            var summary = document.getElementById('returnModalDocSummary');
            if (summary) {
                var html = '<i class="fas fa-info-circle me-1"></i>';
                if (approvedCount > 0) {
                    html += '<strong>' + approvedCount + ' document(s) validé(s)</strong> seront conservés. ';
                }
                if (rejectedCount > 0) {
                    html += '<strong>' + rejectedCount + ' document(s) rejeté(s)</strong> devront être resoumis par l\'utilisateur. ';
                }
                var pendingCount = fields.length - approvedCount - rejectedCount;
                if (pendingCount > 0) {
                    html += pendingCount + ' document(s) non évalué(s) seront marqués comme rejetés.';
                }
                summary.innerHTML = html;
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/verifications/show.blade.php ENDPATH**/ ?>