

<?php $__env->startSection('title', 'Vérifications de profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="h4 fw-bold">
            <i class="fas fa-shield-alt me-2 text-primary"></i>Vérifications de profil
        </h2>
        <p class="text-muted mb-0">Gérer les demandes de vérification d'identité</p>
    </div>
</div>

<!-- Cartes de statistiques -->
<div class="row g-3 mb-4">
    <div class="col-xl col-sm-6">
        <div class="card border-0 shadow-sm <?php echo e(!request('status') ? 'border-start border-warning border-3' : ''); ?>">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.15);">
                    <i class="fas fa-tasks" style="color: #f59e0b; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted small">À traiter</div>
                    <div class="fw-bold fs-4"><?php echo e($stats['to_process']); ?></div>
                    <small class="text-muted"><?php echo e($stats['pending']); ?> nouvelle(s) · <?php echo e($stats['returned']); ?> renvoyée(s)<?php if($stats['resubmitted'] > 0): ?> · <span class="text-success fw-bold"><?php echo e($stats['resubmitted']); ?> corrigée(s)</span><?php endif; ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl col-sm-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.15);">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted small">Approuvées</div>
                    <div class="fw-bold fs-4"><?php echo e($stats['approved']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl col-sm-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.15);">
                    <i class="fas fa-times-circle" style="color: #ef4444; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted small">Rejetées</div>
                    <div class="fw-bold fs-4"><?php echo e($stats['rejected']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl col-sm-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.15);">
                    <i class="fas fa-file-alt" style="color: #6366f1; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total</div>
                    <div class="fw-bold fs-4"><?php echo e($stats['total']); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.verifications')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom ou email..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Statut</label>
                <select name="status" class="form-select">
                    <option value="">À traiter (toutes)</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Nouvelles (en attente)</option>
                    <option value="resubmitted" <?php echo e(request('status') == 'resubmitted' ? 'selected' : ''); ?>>✅ Corrigées (resoumises)</option>
                    <option value="returned" <?php echo e(request('status') == 'returned' ? 'selected' : ''); ?>>Renvoyées (en attente correction)</option>
                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approuvées</option>
                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejetées</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Paiement</label>
                <select name="payment" class="form-select">
                    <option value="">Tous</option>
                    <option value="paid" <?php echo e(request('payment') == 'paid' ? 'selected' : ''); ?>>Payé</option>
                    <option value="pending" <?php echo e(request('payment') == 'pending' ? 'selected' : ''); ?>>Non payé</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Type</label>
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="profile_verification" <?php echo e(request('type') == 'profile_verification' ? 'selected' : ''); ?>>Profil (10€)</option>
                    <option value="service_provider" <?php echo e(request('type') == 'service_provider' ? 'selected' : ''); ?>>Prestataire (10€)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des vérifications -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if($verifications->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Document</th>
                            <th>Montant</th>
                            <th>Soumis le</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $verifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="<?php echo e($v->isResubmission() && $v->isPending() ? 'background: #ecfdf5; border-left: 3px solid #10b981;' : ($v->isReturned() ? 'background: #fffbeb; border-left: 3px solid #f59e0b;' : '')); ?>">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($v->user && $v->user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/' . $v->user->avatar)); ?>" alt="" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem; font-weight: 600;">
                                            <?php echo e($v->user ? strtoupper(substr($v->user->name, 0, 1)) : '?'); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($v->user->name ?? 'Utilisateur supprimé'); ?></div>
                                        <small class="text-muted"><?php echo e($v->user->email ?? '-'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($v->type === 'profile_verification'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info">Profil</span>
                                <?php else: ?>
                                    <span class="badge bg-purple bg-opacity-10 text-purple" style="background: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important;">Prestataire</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $docTypes = ['id_card' => 'Carte d\'identité', 'passport' => 'Passeport', 'driver_license' => 'Permis de conduire', 'cni' => 'Carte d\'identité', 'permis' => 'Permis de conduire', 'carte_sejour' => 'Carte de séjour'];
                                ?>
                                <small><?php echo e($docTypes[$v->document_type] ?? $v->document_type); ?></small>
                            </td>
                            <td>
                                <span class="fw-semibold"><?php echo e(number_format($v->payment_amount, 2)); ?>€</span>
                                <?php if($v->isPaid()): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning"><i class="fas fa-clock me-1"></i>Non payé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo e($v->submitted_at ? $v->submitted_at->format('d/m/Y H:i') : ($v->created_at ? $v->created_at->format('d/m/Y H:i') : '-')); ?></small>
                            </td>
                            <td>
                                <?php if($v->status === 'pending' && $v->isResubmission()): ?>
                                    <span class="badge" style="background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;">
                                        <i class="fas fa-sync-alt me-1"></i>Corrigée
                                    </span>
                                    <br><small class="text-muted">Resoumise le <?php echo e($v->resubmitted_at ? $v->resubmitted_at->format('d/m H:i') : '-'); ?></small>
                                <?php elseif($v->status === 'pending'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Nouvelle
                                    </span>
                                <?php elseif($v->status === 'returned'): ?>
                                    <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fbbf24;">
                                        <i class="fas fa-undo-alt me-1"></i>Renvoyée
                                    </span>
                                    <br><small class="text-muted">En attente de correction</small>
                                <?php elseif($v->status === 'approved'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Approuvée
                                    </span>
                                <?php elseif($v->status === 'rejected'): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Rejetée
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.verifications.show', $v->id)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                    <?php if($v->isPending() || $v->isReturned()): ?>
                                        <form action="<?php echo e(route('admin.verifications.approve', $v->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approuver cette vérification ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($v->user && Auth::id() !== $v->user->id): ?>
                                        <form action="<?php echo e(route('admin.users.delete', $v->user->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer l\'utilisateur <?php echo e(addslashes($v->user->name)); ?> et toutes ses données ? Cette action est irréversible.')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <?php echo e($verifications->withQueryString()->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-check-double fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucune vérification trouvée</h5>
                <p class="text-muted">
                    <?php if(!request()->has('status') && !request()->has('search')): ?>
                        Aucune demande de vérification en attente pour le moment.
                    <?php else: ?>
                        Aucun résultat pour les filtres sélectionnés.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/verifications/index.blade.php ENDPATH**/ ?>