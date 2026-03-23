

<?php $__env->startSection('title', 'Comptes supprimés'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="h4 fw-bold">
            <i class="fas fa-user-slash text-danger me-2"></i>Comptes Supprimés
        </h2>
        <p class="text-muted mb-0">Historique des comptes utilisateurs supprimés avec possibilité de restauration</p>
    </div>
</div>

<!-- Liste des comptes supprimés -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if(isset($deletedUsers) && $deletedUsers->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 ps-4">ID</th>
                        <th class="border-0 py-3">Utilisateur</th>
                        <th class="border-0 py-3">Email original</th>
                        <th class="border-0 py-3">Type</th>
                        <th class="border-0 py-3">Motif de suppression</th>
                        <th class="border-0 py-3">Dates</th>
                        <th class="border-0 py-3 pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $deletedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $log = $deletionLogs[$user->id] ?? null;
                        $originalName = $log->name ?? $user->name;
                        $originalEmail = $log->email ?? $user->email;
                        $reason = $log->reason ?? 'Non spécifié';
                        $accountType = $log->account_type ?? 'particulier';
                        $dataSummary = $log ? json_decode($log->data_summary, true) : null;
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo e($user->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-secondary text-white me-2">
                                    <?php echo e(strtoupper(substr($originalName, 0, 1))); ?>

                                </div>
                                <div>
                                    <strong><?php echo e($originalName); ?></strong>
                                    <?php if($dataSummary): ?>
                                        <br><small class="text-muted">
                                            <?php echo e($dataSummary['ads_count'] ?? 0); ?> annonces · 
                                            <?php echo e($dataSummary['messages_count'] ?? 0); ?> messages · 
                                            <?php echo e($dataSummary['reviews_received'] ?? 0); ?> avis reçus
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted"><?php echo e($originalEmail); ?></span></td>
                        <td>
                            <?php if($accountType === 'professionnel'): ?>
                                <span class="badge" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; font-size: 0.72rem;">PRO</span>
                            <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.72rem;">Particulier</span>
                            <?php endif; ?>
                            <?php if($dataSummary && !empty($dataSummary['is_service_provider'])): ?>
                                <span class="badge bg-info text-white" style="font-size: 0.7rem;">Prestataire</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $reasonColors = [
                                    'Mauvaise expérience' => 'danger',
                                    'Je n\'utilise plus le service' => 'secondary',
                                    'Problèmes de confidentialité' => 'warning',
                                    'Trop de notifications' => 'info',
                                    'Autre' => 'dark',
                                ];
                                $badgeColor = $reasonColors[$reason] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo e($badgeColor); ?>" style="font-size: 0.75rem;">
                                <?php echo e($reason); ?>

                            </span>
                        </td>
                        <td>
                            <small class="text-muted d-block">Inscrit : <?php echo e($user->created_at->format('d/m/Y')); ?></small>
                            <span class="text-danger" style="font-size: 0.85rem;">
                                Supprimé : <?php echo e($user->deleted_at->format('d/m/Y H:i')); ?>

                            </span>
                            <br>
                            <small class="text-muted"><?php echo e($user->deleted_at->diffForHumans()); ?></small>
                        </td>
                        <td class="pe-4 text-end">
                            <form action="<?php echo e(route('admin.accounts.restore', $user->id)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Restaurer le compte de <?php echo e($user->name); ?> ?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                                    <i class="fas fa-undo me-1"></i>Restaurer
                                </button>
                            </form>
                            
                            <form action="<?php echo e(route('admin.accounts.force-delete', $user->id)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('ATTENTION: Cette action est IRRÉVERSIBLE ! Supprimer définitivement le compte de <?php echo e($user->name); ?> et toutes ses données ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer définitivement">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <?php if($deletedUsers->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3">
            <?php echo e($deletedUsers->links()); ?>

        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-user-check fa-4x text-success mb-4"></i>
            <h5 class="text-muted">Aucun compte supprimé</h5>
            <p class="text-muted mb-0">
                Tous les comptes utilisateurs sont actifs.<br>
                Les comptes supprimés apparaîtront ici avec la possibilité de les restaurer.
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Info sur le soft delete -->
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Note:</strong> Les comptes supprimés sont conservés pendant 30 jours avant d'être automatiquement purgés. 
    Vous pouvez restaurer un compte à tout moment pendant cette période.
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/deleted-accounts.blade.php ENDPATH**/ ?>