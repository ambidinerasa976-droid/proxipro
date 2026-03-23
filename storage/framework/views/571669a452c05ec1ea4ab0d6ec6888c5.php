

<?php $__env->startSection('title', 'Gestion des Abonnements'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="h4 fw-bold">
            <i class="fas fa-crown text-warning me-2"></i>Gestion des Abonnements
        </h2>
        <p class="text-muted mb-0">Gérer les abonnements et accorder le premium aux utilisateurs</p>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-4 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-primary bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Total Premium</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['total_premium']); ?></h3>
                    </div>
                    <i class="fas fa-crown fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-success bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Actifs</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['active_subscriptions']); ?></h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-danger bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Expirés</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['expired_subscriptions']); ?></h3>
                    </div>
                    <i class="fas fa-times-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-info bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Starter</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['starter_count']); ?></h3>
                    </div>
                    <i class="fas fa-star fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-success bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Pro</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['pro_count']); ?></h3>
                    </div>
                    <i class="fas fa-rocket fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card stat-card border-0 bg-warning bg-gradient text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75" style="font-size: 0.75rem;">Business</h6>
                        <h3 class="card-title mb-0"><?php echo e($stats['business_count']); ?></h3>
                    </div>
                    <i class="fas fa-building fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.subscriptions')); ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Rechercher..." 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="plan" class="form-select">
                        <option value="">Tous les plans</option>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('plan') == $key ? 'selected' : ''); ?>>
                                <?php echo e($plan['label']); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="subscription_status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" <?php echo e(request('subscription_status') == 'active' ? 'selected' : ''); ?>>Actif</option>
                        <option value="expired" <?php echo e(request('subscription_status') == 'expired' ? 'selected' : ''); ?>>Expiré</option>
                        <option value="none" <?php echo e(request('subscription_status') == 'none' ? 'selected' : ''); ?>>Sans abonnement</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo e(route('admin.subscriptions')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i>Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 ps-4">Utilisateur</th>
                        <th class="border-0 py-3">Plan actuel</th>
                        <th class="border-0 py-3">Fin d'abonnement</th>
                        <th class="border-0 py-3">Statut</th>
                        <th class="border-0 py-3 pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-2">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                                <div>
                                    <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="text-decoration-none fw-bold">
                                        <?php echo e($user->name); ?>

                                    </a>
                                    <br><small class="text-muted"><?php echo e($user->email); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php $planConfig = $plans[$user->plan ?? 'FREE'] ?? $plans['FREE']; ?>
                            <span class="badge bg-<?php echo e($planConfig['color']); ?> px-3 py-2">
                                <?php echo e($planConfig['label']); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($user->subscription_end): ?>
                                <?php echo e(\Carbon\Carbon::parse($user->subscription_end)->format('d/m/Y H:i')); ?>

                                <br>
                                <small class="text-muted">
                                    <?php if(\Carbon\Carbon::parse($user->subscription_end)->isFuture()): ?>
                                        <?php echo e(\Carbon\Carbon::parse($user->subscription_end)->diffForHumans()); ?>

                                    <?php else: ?>
                                        Expiré <?php echo e(\Carbon\Carbon::parse($user->subscription_end)->diffForHumans()); ?>

                                    <?php endif; ?>
                                </small>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($user->subscription_end && \Carbon\Carbon::parse($user->subscription_end)->isFuture()): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            <?php elseif($user->subscription_end): ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Expiré
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-minus-circle me-1"></i>Gratuit
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <!-- Modifier le plan -->
                                <button class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?php echo e($user->id); ?>"
                                        title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Accorder Premium -->
                                <button class="btn btn-sm btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#grantModal<?php echo e($user->id); ?>"
                                        title="Accorder Premium">
                                    <i class="fas fa-crown"></i>
                                </button>
                                
                                <?php if($user->plan !== 'FREE' && ($user->subscription_end && \Carbon\Carbon::parse($user->subscription_end)->isFuture())): ?>
                                <!-- Suspendre -->
                                <form action="<?php echo e(route('admin.subscriptions.suspend', $user->id)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Suspendre l\'abonnement de <?php echo e($user->name); ?> ?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Suspendre">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if($user->plan !== 'FREE'): ?>
                                <!-- Annuler -->
                                <form action="<?php echo e(route('admin.subscriptions.cancel', $user->id)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Annuler l\'abonnement de <?php echo e($user->name); ?> et revenir au plan gratuit ?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Annuler">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Modifier -->
                    <div class="modal fade" id="editModal<?php echo e($user->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier l'abonnement de <?php echo e($user->name); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?php echo e(route('admin.subscriptions.update', $user->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Plan</label>
                                            <select name="plan" class="form-select">
                                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e(($user->plan ?? 'FREE') == $key ? 'selected' : ''); ?>>
                                                        <?php echo e($plan['label']); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Date d'expiration</label>
                                            <input type="datetime-local" name="subscription_end" class="form-control"
                                                   value="<?php echo e($user->subscription_end ? \Carbon\Carbon::parse($user->subscription_end)->format('Y-m-d\TH:i') : ''); ?>">
                                            <small class="text-muted">Laisser vide si le plan est gratuit</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Accorder Premium -->
                    <div class="modal fade" id="grantModal<?php echo e($user->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-warning bg-opacity-25">
                                    <h5 class="modal-title">
                                        <i class="fas fa-crown text-warning me-2"></i>
                                        Accorder Premium à <?php echo e($user->name); ?>

                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?php echo e(route('admin.subscriptions.grant-premium', $user->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Cette action accorde le statut premium gratuitement à l'utilisateur.
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Plan à accorder</label>
                                            <div class="row g-2">
                                                <?php $__currentLoopData = ['STARTER' => 'Starter', 'PRO' => 'Pro', 'BUSINESS' => 'Business']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-4">
                                                    <input type="radio" class="btn-check" name="plan" id="plan<?php echo e($key); ?><?php echo e($user->id); ?>" value="<?php echo e($key); ?>" <?php echo e($key == 'PRO' ? 'checked' : ''); ?>>
                                                    <label class="btn btn-outline-<?php echo e($plans[$key]['color'] ?? 'primary'); ?> w-100" for="plan<?php echo e($key); ?><?php echo e($user->id); ?>">
                                                        <?php echo e($label); ?>

                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Durée</label>
                                            <select name="duration" class="form-select">
                                                <option value="7">7 jours</option>
                                                <option value="30" selected>1 mois</option>
                                                <option value="90">3 mois</option>
                                                <option value="365">1 an</option>
                                                <option value="unlimited">Illimité</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-crown me-2"></i>Accorder Premium
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($users->hasPages()): ?>
    <div class="card-footer bg-white border-0 py-3">
        <?php echo e($users->links()); ?>

    </div>
    <?php endif; ?>
</div>

<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/subscriptions/index.blade.php ENDPATH**/ ?>